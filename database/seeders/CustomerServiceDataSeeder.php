<?php

namespace Database\Seeders;

use App\Models\CustomerFeedback;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CustomerServiceDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create CS Supervisor and Agents
        $csSupervisor = User::where('email', 'admin@tulipstore.com')->first();
        if ($csSupervisor) {
            $csSupervisor->update([
                'is_cs_supervisor' => true,
                'is_cs_agent' => true,
            ]);
        }

        // Create CS Agent
        $csAgent = User::updateOrCreate(
            ['email' => 'cs.agent@tulipstore.com'],
            [
                'name' => 'وكيل خدمة العملاء',
                'username' => 'cs_agent',
                'password' => bcrypt('password'),
                'is_cs_agent' => true,
            ]
        );

        // Get some customers
        $customers = User::where('is_admin', false)
            ->where('is_cs_agent', false)
            ->take(5)
            ->get();

        if ($customers->isEmpty()) {
            $this->command->warn('No customers found. Creating sample customers...');
            for ($i = 1; $i <= 5; $i++) {
                $customers->push(User::create([
                    'name' => "عميل $i",
                    'username' => "customer$i",
                    'email' => "customer$i@example.com",
                    'password' => bcrypt('password'),
                ]));
            }
        }

        // Create Support Tickets
        $tickets = [
            [
                'user_id' => $customers[0]->id,
                'assigned_to' => $csAgent->id,
                'subject' => 'مشكلة في الدفع',
                'description' => 'لم أتمكن من إتمام عملية الدفع باستخدام بطاقتي الائتمانية',
                'category' => 'payment',
                'priority' => 'high',
                'status' => 'in_progress',
                'first_response_at' => Carbon::now()->subHours(2),
                'created_at' => Carbon::now()->subHours(3),
            ],
            [
                'user_id' => $customers[1]->id,
                'assigned_to' => $csAgent->id,
                'subject' => 'استفسار عن حالة الطلب',
                'description' => 'أريد معرفة متى سيصل طلبي رقم #ORD-12345',
                'category' => 'order',
                'priority' => 'medium',
                'status' => 'resolved',
                'first_response_at' => Carbon::now()->subDays(2),
                'resolved_at' => Carbon::now()->subDay(),
                'satisfaction_rating' => 5,
                'satisfaction_comment' => 'خدمة ممتازة وسريعة',
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'user_id' => $customers[2]->id,
                'assigned_to' => null,
                'subject' => 'منتج تالف عند الاستلام',
                'description' => 'استلمت المنتج وكان تالفاً. أريد استبداله',
                'category' => 'product',
                'priority' => 'urgent',
                'status' => 'open',
                'created_at' => Carbon::now()->subMinutes(30),
            ],
            [
                'user_id' => $customers[3]->id,
                'assigned_to' => $csAgent->id,
                'subject' => 'تأخير في الشحن',
                'description' => 'طلبي متأخر عن الموعد المحدد للتسليم',
                'category' => 'shipping',
                'priority' => 'high',
                'status' => 'waiting_customer',
                'first_response_at' => Carbon::now()->subHours(5),
                'created_at' => Carbon::now()->subHours(6),
            ],
            [
                'user_id' => $customers[4]->id,
                'assigned_to' => $csAgent->id,
                'subject' => 'كيفية استخدام كوبون الخصم',
                'description' => 'لدي كوبون خصم ولا أعرف كيف أستخدمه',
                'category' => 'other',
                'priority' => 'low',
                'status' => 'resolved',
                'first_response_at' => Carbon::now()->subDays(1),
                'resolved_at' => Carbon::now()->subHours(12),
                'satisfaction_rating' => 4,
                'created_at' => Carbon::now()->subDays(2),
            ],
        ];

        foreach ($tickets as $ticketData) {
            $ticket = SupportTicket::create($ticketData);

            // Add replies for some tickets
            if ($ticket->status !== 'open') {
                TicketReply::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => $ticket->assigned_to ?? $csAgent->id,
                    'message' => 'شكراً لتواصلك معنا. نحن نعمل على حل مشكلتك الآن.',
                    'is_internal_note' => false,
                    'created_at' => $ticket->first_response_at,
                ]);

                if ($ticket->status === 'resolved' || $ticket->status === 'waiting_customer') {
                    TicketReply::create([
                        'ticket_id' => $ticket->id,
                        'user_id' => $ticket->user_id,
                        'message' => 'شكراً لكم على المساعدة',
                        'is_internal_note' => false,
                        'created_at' => $ticket->first_response_at->addHours(1),
                    ]);
                }
            }
        }

        // Create Customer Feedback
        $feedbacks = [
            [
                'user_id' => $customers[0]->id,
                'type' => 'compliment',
                'rating' => 5,
                'message' => 'خدمة رائعة وتوصيل سريع. شكراً لكم!',
                'status' => 'reviewed',
                'reviewed_by' => $csSupervisor->id,
                'reviewed_at' => Carbon::now()->subDay(),
                'response' => 'شكراً لك على كلماتك الطيبة. نسعد بخدمتك دائماً',
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'user_id' => $customers[1]->id,
                'type' => 'complaint',
                'rating' => 2,
                'message' => 'التوصيل كان متأخراً جداً',
                'status' => 'resolved',
                'reviewed_by' => $csSupervisor->id,
                'reviewed_at' => Carbon::now()->subHours(12),
                'response' => 'نعتذر عن التأخير. سنعمل على تحسين خدمة التوصيل',
                'created_at' => Carbon::now()->subDay(),
            ],
            [
                'user_id' => $customers[2]->id,
                'type' => 'suggestion',
                'rating' => 4,
                'message' => 'أقترح إضافة المزيد من خيارات الدفع',
                'status' => 'reviewed',
                'reviewed_by' => $csSupervisor->id,
                'reviewed_at' => Carbon::now()->subHours(6),
                'response' => 'شكراً على اقتراحك. سننظر في إضافة المزيد من خيارات الدفع',
                'created_at' => Carbon::now()->subHours(8),
            ],
            [
                'user_id' => $customers[3]->id,
                'type' => 'inquiry',
                'rating' => null,
                'message' => 'هل تقدمون خدمة التوصيل الدولي؟',
                'status' => 'pending',
                'created_at' => Carbon::now()->subHours(2),
            ],
        ];

        foreach ($feedbacks as $feedback) {
            CustomerFeedback::create($feedback);
        }

        $this->command->info('Customer Service data seeded successfully!');
    }
}
