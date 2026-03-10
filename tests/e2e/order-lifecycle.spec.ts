import { test, expect } from '@playwright/test';
import {
  apiLogin,
  apiPost,
  createDriverAndAssignment,
  createOrder,
  getCsrfToken,
  setSystemSetting,
  transitionOrder,
} from '../playwright/helpers.js';

/**
 * Create order via __test endpoint (bypasses cart/inventory).
 * Use when createOrder fails due to no product stock.
 */
async function createTestOrderViaApi(
  page: { request: { post: (url: string, opts?: object) => Promise<{ ok: () => boolean; json: () => Promise<{ order_id: number }> }> } },
  csrf: string
): Promise<number> {
  const res = await page.request.post('/__test/orders/create', {
    headers: { 'X-CSRF-TOKEN': csrf },
    form: {},
  });
  if (!res.ok()) {
    throw new Error(`Failed to create test order: ${res.status()}`);
  }
  const json = await res.json();
  if (!json?.order_id) {
    throw new Error(`Test order create missing order_id: ${JSON.stringify(json)}`);
  }
  return json.order_id;
}

/**
 * Full order lifecycle E2E test across dashboards and CS completion.
 *
 * Flow: create order → transition to out_for_delivery → assign driver →
 * driver pickup → in-transit → deliver → CS marks as done.
 *
 * Uses the app's __test endpoints and helpers (no env vars required).
 */
test('order lifecycle: create → out_for_delivery → assigned → pickup → in-transit → delivered → done (CS)', async ({
  page,
}) => {
  await page.goto('/');
  const csrf = await getCsrfToken(page);

  // Ensure test employees exist
  await page.request.get('/create-test-employees');

  // Enable CS ability to complete delivered → done
  await setSystemSetting(page, csrf, {
    key: 'feature.cs_complete_delivered_to_completed',
    value: 'true',
    type: 'boolean',
  });

  // 1. Create order (try cart first, fallback to __test when no stock)
  let orderId: number;
  try {
    const created = await createOrder(page, csrf);
    orderId = Number(created.orderId);
  } catch {
    orderId = await createTestOrderViaApi(page, csrf);
  }
  expect(orderId).toBeTruthy();

  // 2. Transition to out_for_delivery (admin override)
  await transitionOrder(page, csrf, orderId, 'out_for_delivery', true);

  // 3. Create driver and assign to order
  const created = await createDriverAndAssignment(page, csrf, orderId);
  const assignmentId = created?.assignment?.id;
  expect(assignmentId).toBeTruthy();

  // 4. Driver: pickup
  const token = await apiLogin(page, {
    email: created.driver.email,
    password: created.driver.password,
  });
  const pickupRes = await apiPost(
    page,
    `/api/delivery/assignments/${assignmentId}/pickup`,
    token,
    { notes: 'picked up' }
  );
  expect(pickupRes.ok()).toBeTruthy();

  // 5. Driver: in-transit
  const inTransitRes = await apiPost(
    page,
    `/api/delivery/assignments/${assignmentId}/in-transit`,
    token,
    {}
  );
  expect(inTransitRes.ok()).toBeTruthy();

  // 6. Driver: deliver
  const deliverRes = await apiPost(
    page,
    `/api/delivery/assignments/${assignmentId}/deliver`,
    token,
    { notes: 'delivered' }
  );
  expect(deliverRes.ok()).toBeTruthy();

  // 7. CS: login and mark order as done
  await page.goto('/employee/login');
  await page.fill('input[name="email"]', 'support@tulipstore.com');
  await page.fill('input[name="password"]', 'password123');
  await page.click('button[type="submit"]');

  await page.goto(`/dashboard/cs/orders/${orderId}`);
  await expect(page.locator('text=تغيير الحالة')).toBeVisible({ timeout: 5000 });

  const options = page.locator('select[name="status"] option');
  expect(await options.count()).toBeGreaterThan(0);
  const optionValues = await options.evaluateAll((els) =>
    els.map((e) => e.getAttribute('value'))
  );
  expect(optionValues.includes('done')).toBeTruthy();

  await page.selectOption('select[name="status"]', 'done');
  await page.click('button[type="submit"]');

  // 8. Verify order shows as done
  await page.goto(`/dashboard/cs/orders/${orderId}`);
  await expect(page.locator('text=done')).toBeVisible({ timeout: 5000 });
});
