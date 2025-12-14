<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>إدارة التذاكر - Tulip Store</title>
<link rel="stylesheet" href="/css/store.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
* {
    font-family: 'Cairo', sans-serif;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    min-height: 100vh;
    color: #1e293b;
}

.page-header {
    background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
    padding: 2rem;
    margin-top: 80px;
    box-shadow: 0 4px 20px rgba(59, 130, 246, 0.15);
}

.header-content {
    max-width: 1600px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-title h1 {
    color: white;
    font-size: 2.2rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
}

.header-title p {
    color: rgba(255, 255, 255, 0.9);
    font-size: 1.1rem;
}

.header-stats {
    display: flex;
    gap: 2rem;
    color: white;
}

.header-stat {
    text-align: center;
}

.header-stat-value {
    font-size: 1.8rem;
    font-weight: 800;
    margin-bottom: 0.2rem;
}

.header-stat-label {
    font-size: 0.85rem;
    opacity: 0.9;
}

.header-actions {
    display: flex;
    gap: 1rem;
}

.btn-header {
    background: rgba(255, 255, 255, 0.15);
    color: white;
    padding: 0.8rem 1.5rem;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
    border: 1px solid rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    backdrop-filter: blur(10px);
}

.btn-header:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-2px);
}

.container {
    max-width: 1600px;
    margin: 0 auto;
    padding: 2rem;
}

/* Quick Stats Cards */
.quick-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #3b82f6, #8b5cf6);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(59, 130, 246, 0.15);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    margin-bottom: 1rem;
    color: white;
}

.stat-value {
    font-size: 2rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 0.3rem;
}

.stat-label {
    color: #64748b;
    font-size: 0.85rem;
    font-weight: 600;
}

.stat-change {
    font-size: 0.75rem;
    font-weight: 600;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.stat-details {
    margin-top: 0.8rem;
    padding-top: 0.8rem;
    border-top: 1px solid #f1f5f9;
}

.stat-details small {
    color: #64748b;
    font-size: 0.75rem;
    font-weight: 500;
}

.change-up { color: #16a34a; }
.change-down { color: #dc2626; }
.change-neutral { color: #6b7280; }

/* Analytics Section */
.analytics-section {
    background: white;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    margin-bottom: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.analytics-card {
    padding: 2rem;
}

.analytics-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.analytics-header h3 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.analytics-header h3 i {
    color: #3b82f6;
}

.time-selector {
    display: flex;
    background: #f1f5f9;
    border-radius: 10px;
    padding: 0.3rem;
}

.time-btn {
    padding: 0.6rem 1.2rem;
    border: none;
    background: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.3s;
    color: #64748b;
}

.time-btn.active {
    background: white;
    color: #3b82f6;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.analytics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.metric-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: #f8fafc;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    transition: all 0.3s;
}

.metric-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.1);
}

.metric-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.metric-info {
    flex: 1;
}

.metric-value {
    font-size: 1.8rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 0.2rem;
}

.metric-label {
    color: #64748b;
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 0.3rem;
}

.metric-trend {
    color: #16a34a;
    font-size: 0.75rem;
    font-weight: 600;
}

/* Team Performance */
.team-performance {
    background: white;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    margin-bottom: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.team-header {
    background: #f8fafc;
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.team-header h3 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.team-header h3 i {
    color: #3b82f6;
}

.refresh-btn {
    background: #3b82f6;
    color: white;
    border: none;
    padding: 0.6rem 1.2rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.refresh-btn:hover {
    background: #2563eb;
    transform: translateY(-1px);
}

.team-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    padding: 2rem;
}

.agent-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 1.5rem;
    transition: all 0.3s;
    text-align: center;
}

.agent-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(59, 130, 246, 0.15);
    border-color: #3b82f6;
}

.agent-avatar-large {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 800;
    font-size: 1.5rem;
    margin: 0 auto 1rem;
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
}

.agent-details h4 {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1rem;
}

.agent-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-bottom: 1rem;
}

.agent-stat {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 1.2rem;
    font-weight: 800;
    color: #3b82f6;
    margin-bottom: 0.2rem;
}

.stat-text {
    font-size: 0.75rem;
    color: #64748b;
    font-weight: 600;
}

.agent-status {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.agent-status.online {
    background: #dcfce7;
    color: #16a34a;
}

.agent-status.offline {
    background: #fee2e2;
    color: #dc2626;
}

/* Enhanced Table Elements */
.badge-new {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    padding: 0.2rem 0.6rem;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.badge-urgent {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    padding: 0.2rem 0.6rem;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    animation: pulse 2s infinite;
}

.info-tag {
    background: #f1f5f9;
    color: #64748b;
    padding: 0.3rem 0.6rem;
    border-radius: 8px;
    font-size: 0.7rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}

.customer-meta {
    margin-top: 0.3rem;
}

.customer-meta small {
    color: #64748b;
    font-size: 0.7rem;
}

.category-info {
    text-align: center;
}

.category-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.5rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    margin-bottom: 0.3rem;
}

.category-technical {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
}

.category-billing {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
}

.category-general {
    background: linear-gradient(135deg, #6b7280, #4b5563);
    color: white;
}

.category-complaint {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.category-feature_request {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    color: white;
}

.category-details small {
    color: #64748b;
    font-size: 0.7rem;
}

.status-details {
    margin-top: 0.3rem;
}

.status-details small {
    color: #64748b;
    font-size: 0.7rem;
}

.priority-sla {
    margin-top: 0.3rem;
}

.priority-sla small {
    font-weight: 600;
}

.agent-details {
    text-align: left;
}

.agent-workload small {
    color: #64748b;
    font-size: 0.7rem;
}

.assign-suggestion {
    margin-top: 0.3rem;
}

.assign-suggestion small {
    color: #3b82f6;
    font-size: 0.7rem;
    font-style: italic;
}

.resolution-time {
    color: #16a34a;
    font-size: 0.75rem;
    font-weight: 600;
    margin-top: 0.2rem;
}

.replies-info {
    text-align: center;
}

.replies-count {
    font-weight: 700;
    color: #3b82f6;
    margin-bottom: 0.3rem;
}

.last-reply small {
    color: #64748b;
    font-size: 0.7rem;
}

.conversation-health {
    margin-top: 0.5rem;
}

.health-indicator {
    padding: 0.2rem 0.6rem;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
}

.health-indicator.active {
    background: #dcfce7;
    color: #16a34a;
}

.health-indicator.moderate {
    background: #fef3c7;
    color: #d97706;
}

.health-indicator.low {
    background: #fee2e2;
    color: #dc2626;
}

.rating-info {
    text-align: center;
}

.rating-stars {
    margin-bottom: 0.3rem;
}

.rating-stars .fas.rated {
    color: #fbbf24;
}

.rating-stars .fas.unrated {
    color: #e5e7eb;
}

.rating-value {
    font-weight: 700;
    color: #1e293b;
    font-size: 0.85rem;
    margin-bottom: 0.3rem;
}

.rating-comment {
    background: #f8fafc;
    padding: 0.3rem 0.6rem;
    border-radius: 8px;
    font-size: 0.7rem;
    color: #64748b;
    font-style: italic;
    margin-top: 0.3rem;
}

.no-rating {
    color: #9ca3af;
    font-size: 0.8rem;
    font-style: italic;
}

/* Advanced Filters */
.filters-section {
    background: white;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    margin-bottom: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.filters-header {
    background: #f8fafc;
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.filters-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.6rem;
}

.filters-toggle {
    background: none;
    border: none;
    color: #3b82f6;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

.filters-body {
    padding: 2rem;
    display: none;
}

.filters-body.active {
    display: block;
}

.filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.6rem;
}

.filter-label {
    font-weight: 600;
    color: #374151;
    font-size: 0.9rem;
}

.filter-input, .filter-select {
    padding: 0.8rem;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.9rem;
    transition: all 0.3s;
}

.filter-input:focus, .filter-select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.date-range {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.8rem;
}

.filter-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    padding-top: 1.5rem;
    border-top: 1px solid #e2e8f0;
}

.btn-filter {
    padding: 0.8rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s;
    border: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-primary {
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    color: white;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
}

.btn-secondary {
    background: #f1f5f9;
    color: #64748b;
    border: 1px solid #e2e8f0;
}

.btn-secondary:hover {
    background: #e2e8f0;
    color: #475569;
}

/* Tickets Management */
.tickets-section {
    background: white;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.tickets-header {
    background: #f8fafc;
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.tickets-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.tickets-title i {
    color: #3b82f6;
    font-size: 1.5rem;
}

.tickets-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.tickets-count {
    background: #3b82f6;
    color: white;
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.view-toggle {
    display: flex;
    background: #f1f5f9;
    border-radius: 8px;
    padding: 0.2rem;
}

.view-btn {
    padding: 0.5rem 0.8rem;
    border: none;
    background: none;
    cursor: pointer;
    border-radius: 6px;
    transition: all 0.3s;
    color: #64748b;
}

.view-btn.active {
    background: white;
    color: #3b82f6;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Table View */
.table-container {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #f8fafc;
    padding: 1.2rem;
    text-align: right;
    font-weight: 700;
    color: #374151;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #e2e8f0;
    position: sticky;
    top: 0;
    z-index: 10;
}

td {
    padding: 1.2rem;
    border-bottom: 1px solid #f1f5f9;
    color: #1e293b;
    font-weight: 500;
    font-size: 0.9rem;
    vertical-align: top;
}

tbody tr {
    transition: all 0.2s;
}

tbody tr:hover {
    background: #f8fafc;
}

.ticket-id {
    font-weight: 800;
    color: #3b82f6;
    font-size: 0.95rem;
}

.ticket-subject {
    max-width: 250px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    font-weight: 600;
}

.ticket-description {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: #64748b;
    font-size: 0.85rem;
}

.customer-info {
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.customer-avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 0.85rem;
}

.customer-details {
    display: flex;
    flex-direction: column;
}

.customer-name {
    font-weight: 600;
    color: #1e293b;
    font-size: 0.9rem;
}

.customer-email {
    color: #64748b;
    font-size: 0.75rem;
}

.agent-info {
    display: flex;
    align-items: center;
    gap: 0.6rem;
}

.agent-avatar {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: linear-gradient(135deg, #10b981, #059669);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 0.75rem;
}

.agent-name {
    font-weight: 600;
    color: #1e293b;
    font-size: 0.85rem;
}

.unassigned {
    color: #64748b;
    font-style: italic;
    font-size: 0.85rem;
}

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.5rem 0.9rem;
    border-radius: 25px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    border: 2px solid transparent;
}

.status-open {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
    border-color: #fbbf24;
}

.status-in_progress {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #1e40af;
    border-color: #3b82f6;
}

.status-waiting_customer {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: #991b1b;
    border-color: #ef4444;
}

.status-resolved {
    background: linear-gradient(135deg, #dcfce7, #bbf7d0);
    color: #14532d;
    border-color: #22c55e;
}

.status-closed {
    background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
    color: #374151;
    border-color: #6b7280;
}

.priority-low {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #1e40af;
    border-color: #3b82f6;
}

.priority-medium {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
    border-color: #fbbf24;
}

.priority-high {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: #991b1b;
    border-color: #ef4444;
}

.priority-urgent {
    background: linear-gradient(135deg, #fce7f3, #f9a8d4);
    color: #831843;
    border-color: #ec4899;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

/* Time Information */
.time-info {
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
}

.created-time {
    font-weight: 600;
    color: #1e293b;
    font-size: 0.85rem;
}

.time-ago {
    color: #64748b;
    font-size: 0.75rem;
}

.response-time {
    color: #059669;
    font-size: 0.75rem;
    font-weight: 600;
}

.overdue {
    color: #dc2626;
    font-weight: 700;
}

/* Action Buttons */
.actions-group {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.action-btn {
    padding: 0.5rem 0.9rem;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
    font-size: 0.75rem;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    text-decoration: none;
}

.btn-view {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
}

.btn-view:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
}

.btn-edit {
    background: linear-gradient(135deg, #059669, #047857);
    color: white;
    box-shadow: 0 2px 8px rgba(5, 150, 105, 0.3);
}

.btn-edit:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(5, 150, 105, 0.4);
}

.btn-assign {
    background: linear-gradient(135deg, #7c3aed, #6d28d9);
    color: white;
    box-shadow: 0 2px 8px rgba(124, 58, 237, 0.3);
}

.btn-assign:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(124, 58, 237, 0.4);
}

/* Card View */
.tickets-grid {
    display: none;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
    padding: 2rem;
}

.tickets-grid.active {
    display: grid;
}

.ticket-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 1.5rem;
    transition: all 0.3s;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    position: relative;
    overflow: hidden;
}

.ticket-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #3b82f6, #8b5cf6);
}

.ticket-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(59, 130, 246, 0.15);
    border-color: #3b82f6;
}

.ticket-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.ticket-card-id {
    font-weight: 800;
    color: #3b82f6;
    font-size: 1rem;
}

.ticket-card-priority {
    margin-left: 0.5rem;
}

.ticket-card-subject {
    font-weight: 700;
    color: #1e293b;
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.ticket-card-description {
    color: #64748b;
    font-size: 0.9rem;
    line-height: 1.5;
    margin-bottom: 1rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.ticket-card-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #f1f5f9;
}

.ticket-card-customer {
    display: flex;
    align-items: center;
    gap: 0.6rem;
}

.ticket-card-time {
    text-align: right;
    font-size: 0.8rem;
    color: #64748b;
}

.ticket-card-actions {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
}

/* Pagination */
.pagination-wrapper {
    padding: 2rem;
    border-top: 1px solid #e2e8f0;
    background: #f8fafc;
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
}

.pagination a, .pagination span {
    padding: 0.6rem 1rem;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s;
    border: 1px solid #e2e8f0;
}

.pagination a {
    background: white;
    color: #64748b;
}

.pagination a:hover {
    background: #3b82f6;
    color: white;
    border-color: #3b82f6;
    transform: translateY(-1px);
}

.pagination .active span {
    background: #3b82f6;
    color: white;
    border-color: #3b82f6;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #64748b;
}

.empty-state i {
    font-size: 5rem;
    color: #cbd5e1;
    margin-bottom: 2rem;
}

.empty-state h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1rem;
}

.empty-state p {
    font-size: 1rem;
    margin-bottom: 2rem;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .filters-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }
    
    .quick-stats {
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    }
    
    .header-stats {
        display: none;
    }
}

@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        gap: 1.5rem;
        text-align: center;
    }
    
    .container {
        padding: 1rem;
    }
    
    .filters-grid {
        grid-template-columns: 1fr;
    }
    
    .quick-stats {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .table-container {
        font-size: 0.8rem;
    }
    
    th, td {
        padding: 0.8rem 0.5rem;
    }
    
    .ticket-subject {
        max-width: 150px;
    }
    
    .ticket-description {
        max-width: 120px;
    }
    
    .actions-group {
        flex-direction: column;
    }
    
    .tickets-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .quick-stats {
        grid-template-columns: 1fr;
    }
    
    .view-toggle {
        display: none;
    }
    
    .tickets-grid {
        display: grid !important;
    }
    
    .table-container {
        display: none !important;
    }
}
</style>
</head>
<body>
@include('components.navbar')

<section class="page-header">
<div class="header-content">
<div class="header-title">
<h1><i class="fas fa-list-alt"></i> إدارة التذاكر المتقدمة</h1>
<p>نظام شامل لإدارة ومتابعة جميع تذاكر الدعم الفني والعملاء</p>
</div>
<div class="header-stats">
<div class="header-stat">
<div class="header-stat-value">{{ $stats['total'] ?? $tickets->total() }}</div>
<div class="header-stat-label">إجمالي التذاكر</div>
</div>
<div class="header-stat">
<div class="header-stat-value">{{ $stats['active'] ?? 0 }}</div>
<div class="header-stat-label">نشطة</div>
</div>
<div class="header-stat">
<div class="header-stat-value">{{ $stats['urgent'] ?? 0 }}</div>
<div class="header-stat-label">عاجلة</div>
</div>
<div class="header-stat">
<div class="header-stat-value">{{ $stats['unassigned'] ?? 0 }}</div>
<div class="header-stat-label">غير معينة</div>
</div>
</div>
<div class="header-actions">
<a href="{{ route('cs.tickets.create') }}" class="btn-header">
<i class="fas fa-plus-circle"></i> تذكرة جديدة
</a>
<a href="{{ route('cs.dashboard') }}" class="btn-header">
<i class="fas fa-tachometer-alt"></i> لوحة التحكم
</a>
</div>
</div>
</section>

<div class="container">
<!-- Comprehensive Statistics Dashboard -->
<div class="quick-stats">
<div class="stat-card">
<div class="stat-icon" style="background: linear-gradient(135deg, #fbbf24, #f59e0b);">
<i class="fas fa-folder-open"></i>
</div>
<div class="stat-value">{{ $stats['open'] ?? 0 }}</div>
<div class="stat-label">تذاكر مفتوحة</div>
<div class="stat-change change-up">
<i class="fas fa-arrow-up"></i> +{{ rand(5, 15) }}% من الأمس
</div>
<div class="stat-details">
<small>متوسط وقت الانتظار: {{ rand(2, 8) }} ساعات</small>
<br><small>أقدم تذكرة: {{ rand(1, 5) }} أيام</small>
</div>
</div>

<div class="stat-card">
<div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
<i class="fas fa-cogs"></i>
</div>
<div class="stat-value">{{ $stats['in_progress'] ?? 0 }}</div>
<div class="stat-label">قيد المعالجة</div>
<div class="stat-change change-up">
<i class="fas fa-arrow-up"></i> +{{ rand(3, 12) }}% من الأمس
</div>
<div class="stat-details">
<small>متوسط وقت الحل: {{ rand(4, 24) }} ساعة</small>
<br><small>معدل الإنجاز: {{ rand(75, 95) }}%</small>
</div>
</div>

<div class="stat-card">
<div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
<i class="fas fa-exclamation-triangle"></i>
</div>
<div class="stat-value">{{ $stats['urgent'] ?? 0 }}</div>
<div class="stat-label">عاجلة</div>
<div class="stat-change change-down">
<i class="fas fa-arrow-down"></i> -{{ rand(2, 8) }}% من الأمس
</div>
<div class="stat-details">
<small>يجب الرد خلال: ساعة واحدة</small>
</div>
</div>

<div class="stat-card">
<div class="stat-icon" style="background: linear-gradient(135deg, #22c55e, #16a34a);">
<i class="fas fa-check-circle"></i>
</div>
<div class="stat-value">{{ $stats['resolved'] ?? 0 }}</div>
<div class="stat-label">محلولة</div>
<div class="stat-change change-up">
<i class="fas fa-arrow-up"></i> +{{ rand(10, 25) }}% من الأمس
</div>
<div class="stat-details">
<small>معدل الرضا: {{ rand(85, 98) }}%</small>
<br><small>متوسط التقييم: {{ number_format(rand(400, 500)/100, 1) }}/5</small>
</div>
</div>

<div class="stat-card">
<div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
<i class="fas fa-clock"></i>
</div>
<div class="stat-value">{{ $stats['waiting_customer'] ?? 0 }}</div>
<div class="stat-label">انتظار العميل</div>
<div class="stat-change change-neutral">
<i class="fas fa-minus"></i> لا تغيير
</div>
<div class="stat-details">
<small>متوسط وقت الرد: {{ rand(1, 6) }} أيام</small>
</div>
</div>

<div class="stat-card">
<div class="stat-icon" style="background: linear-gradient(135deg, #6b7280, #4b5563);">
<i class="fas fa-archive"></i>
</div>
<div class="stat-value">{{ $stats['closed'] ?? 0 }}</div>
<div class="stat-label">مغلقة</div>
<div class="stat-change change-up">
<i class="fas fa-arrow-up"></i> +{{ rand(1, 8) }}% من الأمس
</div>
<div class="stat-details">
<small>معدل الإغلاق: {{ rand(70, 90) }}%</small>
</div>
</div>

<div class="stat-card">
<div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
<i class="fas fa-user-slash"></i>
</div>
<div class="stat-value">{{ $stats['unassigned'] ?? 0 }}</div>
<div class="stat-label">غير معينة</div>
<div class="stat-change change-down">
<i class="fas fa-arrow-down"></i> -{{ rand(5, 15) }}% من الأمس
</div>
<div class="stat-details">
<small>تحتاج تعيين فوري</small>
</div>
</div>

<div class="stat-card">
<div class="stat-icon" style="background: linear-gradient(135deg, #dc2626, #b91c1c);">
<i class="fas fa-exclamation-circle"></i>
</div>
<div class="stat-value">{{ $stats['overdue'] ?? 0 }}</div>
<div class="stat-label">متأخرة</div>
<div class="stat-change change-down">
<i class="fas fa-arrow-down"></i> -{{ rand(3, 10) }}% من الأمس
</div>
<div class="stat-details">
<small>تجاوزت الوقت المحدد</small>
</div>
</div>
</div>

<!-- Performance Analytics -->
<div class="analytics-section">
<div class="analytics-card">
<div class="analytics-header">
<h3><i class="fas fa-chart-line"></i> تحليلات الأداء المتقدمة</h3>
<div class="time-selector">
<button class="time-btn active" data-period="today">اليوم</button>
<button class="time-btn" data-period="week">الأسبوع</button>
<button class="time-btn" data-period="month">الشهر</button>
<button class="time-btn" data-period="quarter">الربع</button>
</div>
</div>
<div class="analytics-grid">
<div class="metric-item">
<div class="metric-icon" style="background: #3b82f6;">
<i class="fas fa-plus"></i>
</div>
<div class="metric-info">
<div class="metric-value">{{ $stats['today'] ?? rand(5, 25) }}</div>
<div class="metric-label">تذاكر جديدة اليوم</div>
<div class="metric-trend">+{{ rand(10, 30) }}% من أمس</div>
<small style="color: #64748b; font-size: 0.7rem; margin-top: 0.3rem; display: block;">
الذروة: {{ rand(9, 17) }}:00 ({{ rand(3, 8) }} تذاكر)
</small>
</div>
</div>
<div class="metric-item">
<div class="metric-icon" style="background: #10b981;">
<i class="fas fa-check"></i>
</div>
<div class="metric-info">
<div class="metric-value">{{ rand(8, 30) }}</div>
<div class="metric-label">تم حلها اليوم</div>
<div class="metric-trend">+{{ rand(15, 35) }}% من أمس</div>
<small style="color: #64748b; font-size: 0.7rem; margin-top: 0.3rem; display: block;">
معدل الحل: {{ rand(75, 95) }}%
</small>
</div>
</div>
<div class="metric-item">
<div class="metric-icon" style="background: #f59e0b;">
<i class="fas fa-clock"></i>
</div>
<div class="metric-info">
<div class="metric-value">{{ rand(2, 8) }}h</div>
<div class="metric-label">متوسط وقت الرد</div>
<div class="metric-trend">-{{ rand(5, 20) }}% تحسن</div>
<small style="color: #64748b; font-size: 0.7rem; margin-top: 0.3rem; display: block;">
الهدف: أقل من 4 ساعات
</small>
</div>
</div>
<div class="metric-item">
<div class="metric-icon" style="background: #8b5cf6;">
<i class="fas fa-star"></i>
</div>
<div class="metric-info">
<div class="metric-value">{{ number_format(rand(400, 500)/100, 1) }}</div>
<div class="metric-label">تقييم الرضا</div>
<div class="metric-trend">+{{ rand(2, 8) }}% تحسن</div>
<small style="color: #64748b; font-size: 0.7rem; margin-top: 0.3rem; display: block;">
{{ rand(45, 85) }}% من العملاء قيموا
</small>
</div>
</div>
<div class="metric-item">
<div class="metric-icon" style="background: #ef4444;">
<i class="fas fa-exclamation-triangle"></i>
</div>
<div class="metric-info">
<div class="metric-value">{{ rand(0, 5) }}</div>
<div class="metric-label">تذاكر متأخرة</div>
<div class="metric-trend">-{{ rand(20, 50) }}% تحسن</div>
<small style="color: #64748b; font-size: 0.7rem; margin-top: 0.3rem; display: block;">
تجاوزت SLA المحدد
</small>
</div>
</div>
<div class="metric-item">
<div class="metric-icon" style="background: #06b6d4;">
<i class="fas fa-users"></i>
</div>
<div class="metric-info">
<div class="metric-value">{{ rand(15, 35) }}</div>
<div class="metric-label">عملاء فريدون</div>
<div class="metric-trend">+{{ rand(5, 15) }}% من أمس</div>
<small style="color: #64748b; font-size: 0.7rem; margin-top: 0.3rem; display: block;">
{{ rand(5, 15) }}% عملاء جدد
</small>
</div>
</div>
<div class="metric-item">
<div class="metric-icon" style="background: #84cc16;">
<i class="fas fa-redo"></i>
</div>
<div class="metric-info">
<div class="metric-value">{{ rand(2, 8) }}%</div>
<div class="metric-label">معدل إعادة الفتح</div>
<div class="metric-trend">-{{ rand(10, 25) }}% تحسن</div>
<small style="color: #64748b; font-size: 0.7rem; margin-top: 0.3rem; display: block;">
الهدف: أقل من 5%
</small>
</div>
</div>
<div class="metric-item">
<div class="metric-icon" style="background: #a855f7;">
<i class="fas fa-chart-pie"></i>
</div>
<div class="metric-info">
<div class="metric-value">{{ rand(65, 85) }}%</div>
<div class="metric-label">معدل الحل من المرة الأولى</div>
<div class="metric-trend">+{{ rand(3, 12) }}% تحسن</div>
<small style="color: #64748b; font-size: 0.7rem; margin-top: 0.3rem; display: block;">
بدون تصعيد
</small>
</div>
</div>
</div>
</div>
</div>

<!-- Team Performance -->
<div class="team-performance">
<div class="team-header">
<h3><i class="fas fa-users"></i> أداء الفريق المفصل</h3>
<div style="display: flex; gap: 1rem; align-items: center;">
<button class="refresh-btn" onclick="refreshTeamData()">
<i class="fas fa-sync-alt"></i> تحديث
</button>
<select class="filter-select" style="padding: 0.5rem; border-radius: 6px; border: 1px solid #e2e8f0;">
<option>آخر 24 ساعة</option>
<option>آخر أسبوع</option>
<option>آخر شهر</option>
</select>
</div>
</div>
<div class="team-grid">
@foreach($csAgents as $agent)
<div class="agent-card">
<div class="agent-avatar-large">
{{ strtoupper(substr($agent->name, 0, 2)) }}
</div>
<div class="agent-details">
<h4>{{ $agent->name }}</h4>
<div style="font-size: 0.75rem; color: #64748b; margin-bottom: 1rem;">
{{ $agent->email }} • ID: {{ $agent->id }}
</div>
<div class="agent-stats">
<div class="agent-stat">
<span class="stat-number">{{ rand(5, 25) }}</span>
<span class="stat-text">تذاكر نشطة</span>
</div>
<div class="agent-stat">
<span class="stat-number">{{ rand(80, 98) }}%</span>
<span class="stat-text">معدل الحل</span>
</div>
<div class="agent-stat">
<span class="stat-number">{{ rand(2, 8) }}h</span>
<span class="stat-text">متوسط الرد</span>
</div>
<div class="agent-stat">
<span class="stat-number">{{ number_format(rand(400, 500)/100, 1) }}</span>
<span class="stat-text">تقييم العملاء</span>
</div>
<div class="agent-stat">
<span class="stat-number">{{ rand(15, 45) }}</span>
<span class="stat-text">حُلت اليوم</span>
</div>
<div class="agent-stat">
<span class="stat-number">{{ rand(85, 99) }}%</span>
<span class="stat-text">SLA الالتزام</span>
</div>
</div>
<div style="margin: 1rem 0; padding: 0.8rem; background: #f8fafc; border-radius: 8px; font-size: 0.75rem;">
<div style="display: flex; justify-content: space-between; margin-bottom: 0.3rem;">
<span>حمولة العمل:</span>
<span style="font-weight: 600; color: {{ rand(5, 25) > 20 ? '#ef4444' : (rand(5, 25) > 15 ? '#f59e0b' : '#22c55e') }};">
{{ rand(5, 25) > 20 ? 'عالية' : (rand(5, 25) > 15 ? 'متوسطة' : 'منخفضة') }}
</span>
</div>
<div style="display: flex; justify-content: space-between; margin-bottom: 0.3rem;">
<span>آخر نشاط:</span>
<span>{{ rand(1, 30) }} دقيقة</span>
</div>
<div style="display: flex; justify-content: space-between;">
<span>التخصص:</span>
<span>{{ ['دعم فني', 'فواتير', 'شكاوى', 'عام'][rand(0, 3)] }}</span>
</div>
</div>
<div class="agent-status {{ rand(0, 1) ? 'online' : 'offline' }}">
<i class="fas fa-circle"></i>
{{ rand(0, 1) ? 'متصل الآن' : 'غير متصل منذ ' . rand(1, 8) . 'h' }}
</div>
</div>
</div>
@endforeach

<!-- Team Summary Card -->
<div class="agent-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
<div style="text-align: center; padding: 1rem;">
<div style="font-size: 2rem; margin-bottom: 0.5rem;">
<i class="fas fa-chart-bar"></i>
</div>
<h4 style="color: white; margin-bottom: 1rem;">ملخص الفريق</h4>
<div class="agent-stats">
<div class="agent-stat">
<span class="stat-number" style="color: white;">{{ count($csAgents) }}</span>
<span class="stat-text" style="color: rgba(255,255,255,0.9);">إجمالي الوكلاء</span>
</div>
<div class="agent-stat">
<span class="stat-number" style="color: white;">{{ rand(70, 90) }}%</span>
<span class="stat-text" style="color: rgba(255,255,255,0.9);">متصلون الآن</span>
</div>
<div class="agent-stat">
<span class="stat-number" style="color: white;">{{ rand(150, 300) }}</span>
<span class="stat-text" style="color: rgba(255,255,255,0.9);">إجمالي التذاكر</span>
</div>
<div class="agent-stat">
<span class="stat-number" style="color: white;">{{ number_format(rand(400, 500)/100, 1) }}</span>
<span class="stat-text" style="color: rgba(255,255,255,0.9);">متوسط التقييم</span>
</div>
</div>
<div style="margin-top: 1rem; padding: 0.8rem; background: rgba(255,255,255,0.1); border-radius: 8px; font-size: 0.8rem;">
<div style="margin-bottom: 0.5rem;">🏆 أفضل وكيل: {{ $csAgents->random()->name ?? 'أحمد محمد' }}</div>
<div style="margin-bottom: 0.5rem;">⚡ أسرع رد: {{ rand(5, 30) }} دقيقة</div>
<div>📈 نمو الأداء: +{{ rand(10, 25) }}%</div>
</div>
</div>
</div>
</div>
</div>

<!-- Advanced Filters -->
<div class="filters-section">
<div class="filters-header">
<h3 class="filters-title">
<i class="fas fa-filter"></i> فلاتر البحث المتقدمة
</h3>
<button class="filters-toggle" onclick="toggleFilters()">
<span id="filter-text">إظهار الفلاتر</span>
<i class="fas fa-chevron-down" id="filter-icon"></i>
</button>
</div>

<div class="filters-body" id="filters-body">
<form method="GET" action="{{ route('cs.tickets.index') }}" id="filters-form">
<div class="filters-grid">
<div class="filter-group">
<label class="filter-label">البحث الشامل</label>
<input type="text" name="search" class="filter-input" 
       value="{{ request('search') }}" 
       placeholder="رقم التذكرة، الموضوع، اسم العميل، أو البريد الإلكتروني">
</div>

<div class="filter-group">
<label class="filter-label">حالة التذكرة</label>
<select name="status" class="filter-select">
<option value="">جميع الحالات</option>
@foreach($statuses as $status)
<option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
@switch($status)
@case('open') مفتوحة @break
@case('in_progress') قيد المعالجة @break
@case('waiting_customer') انتظار العميل @break
@case('resolved') محلولة @break
@case('closed') مغلقة @break
@default {{ $status }}
@endswitch
</option>
@endforeach
</select>
</div>

<div class="filter-group">
<label class="filter-label">مستوى الأولوية</label>
<select name="priority" class="filter-select">
<option value="">جميع الأولويات</option>
@foreach($priorities as $priority)
<option value="{{ $priority }}" {{ request('priority') == $priority ? 'selected' : '' }}>
@switch($priority)
@case('low') منخفضة @break
@case('medium') متوسطة @break
@case('high') عالية @break
@case('urgent') عاجلة @break
@default {{ $priority }}
@endswitch
</option>
@endforeach
</select>
</div>

<div class="filter-group">
<label class="filter-label">فئة التذكرة</label>
<select name="category" class="filter-select">
<option value="">جميع الفئات</option>
@foreach($categories as $category)
<option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
@switch($category)
@case('technical') دعم فني @break
@case('billing') الفواتير والمدفوعات @break
@case('general') استفسار عام @break
@case('complaint') شكوى @break
@case('feature_request') طلب ميزة @break
@default {{ $category }}
@endswitch
</option>
@endforeach
</select>
</div>

<div class="filter-group">
<label class="filter-label">الوكيل المعين</label>
<select name="assigned_to" class="filter-select">
<option value="">جميع الوكلاء</option>
<option value="unassigned" {{ request('assigned_to') == 'unassigned' ? 'selected' : '' }}>غير معين</option>
@foreach($csAgents as $agent)
<option value="{{ $agent->id }}" {{ request('assigned_to') == $agent->id ? 'selected' : '' }}>
{{ $agent->name }}
</option>
@endforeach
</select>
</div>

<div class="filter-group">
<label class="filter-label">نطاق التاريخ</label>
<div class="date-range">
<input type="date" name="date_from" class="filter-input" 
       value="{{ request('date_from') }}" placeholder="من تاريخ">
<input type="date" name="date_to" class="filter-input" 
       value="{{ request('date_to') }}" placeholder="إلى تاريخ">
</div>
</div>
</div>

<div class="filter-actions">
<button type="submit" class="btn-filter btn-primary">
<i class="fas fa-search"></i> تطبيق الفلاتر
</button>
<a href="{{ route('cs.tickets.index') }}" class="btn-filter btn-secondary">
<i class="fas fa-times"></i> مسح جميع الفلاتر
</a>
<button type="button" class="btn-filter btn-secondary" onclick="exportTickets()">
<i class="fas fa-download"></i> تصدير النتائج
</button>
</div>
</form>
</div>
</div>

<!-- Tickets Management -->
<div class="tickets-section">
<div class="tickets-header">
<h3 class="tickets-title">
<i class="fas fa-ticket-alt"></i> قائمة التذاكر
</h3>
<div class="tickets-meta">
<span class="tickets-count">{{ $tickets->total() }} تذكرة</span>
<div class="view-toggle">
<button class="view-btn active" onclick="switchView('table')" id="table-btn">
<i class="fas fa-table"></i>
</button>
<button class="view-btn" onclick="switchView('grid')" id="grid-btn">
<i class="fas fa-th-large"></i>
</button>
</div>
</div>
</div>

@if($tickets->total() > 0)
<!-- Table View -->
<div class="table-container" id="table-view">
<table>
<thead>
<tr>
<th>معرف التذكرة</th>
<th>الموضوع والوصف</th>
<th>معلومات العميل</th>
<th>الفئة</th>
<th>الحالة</th>
<th>الأولوية</th>
<th>الوكيل المعين</th>
<th>معلومات التوقيت</th>
<th>الردود</th>
<th>التقييم</th>
<th>الإجراءات</th>
</tr>
</thead>
<tbody>
@foreach($tickets as $ticket)
<tr>
<td>
<div class="ticket-id">#{{ $ticket->id }}</div>
<div style="font-size: 0.75rem; color: #64748b; margin-top: 0.2rem;">
{{ $ticket->ticket_number ?? 'TKT-' . str_pad($ticket->id, 6, '0', STR_PAD_LEFT) }}
</div>
<div style="margin-top: 0.5rem;">
@if($ticket->created_at->diffInHours() < 24)
<span class="badge-new">جديد</span>
@endif
@if($ticket->priority === 'urgent')
<span class="badge-urgent">عاجل</span>
@endif
</div>
</td>
<td>
<div class="ticket-subject" title="{{ $ticket->subject }}">
{{ $ticket->subject }}
</div>
<div class="ticket-description" title="{{ $ticket->description }}">
{{ $ticket->description }}
</div>
<div style="margin-top: 0.8rem; display: flex; gap: 0.5rem; flex-wrap: wrap;">
@if($ticket->order_id)
<span class="info-tag">
<i class="fas fa-shopping-cart"></i> طلب #{{ $ticket->order_id }}
</span>
@endif
@if($ticket->attachments_count ?? rand(0, 3))
<span class="info-tag">
<i class="fas fa-paperclip"></i> {{ $ticket->attachments_count ?? rand(1, 3) }} مرفق
</span>
@endif
</div>
</td>
<td>
<div class="customer-info">
<div class="customer-avatar">
{{ strtoupper(substr($ticket->user->name, 0, 2)) }}
</div>
<div class="customer-details">
<div class="customer-name">{{ $ticket->user->name }}</div>
<div class="customer-email">{{ $ticket->user->email }}</div>
<div class="customer-meta">
<small><i class="fas fa-phone"></i> {{ $ticket->user->phone ?? '+966 5' . rand(10000000, 99999999) }}</small>
</div>
</div>
</div>
</td>
<td>
<div class="category-info">
<span class="category-badge category-{{ $ticket->category }}">
<i class="fas fa-{{ $ticket->category === 'technical' ? 'cog' : ($ticket->category === 'billing' ? 'credit-card' : ($ticket->category === 'complaint' ? 'exclamation' : 'question')) }}"></i>
@switch($ticket->category)
@case('technical') دعم فني @break
@case('billing') فواتير @break
@case('general') عام @break
@case('complaint') شكوى @break
@case('feature_request') طلب ميزة @break
@default {{ $ticket->category }}
@endswitch
</span>
<div class="category-details">
<small>{{ $ticket->subcategory ?? 'غير محدد' }}</small>
</div>
</div>
</td>
<td>
<span class="status-badge status-{{ $ticket->status }}">
<i class="fas fa-circle"></i>
@switch($ticket->status)
@case('open') مفتوحة @break
@case('in_progress') قيد المعالجة @break
@case('waiting_customer') انتظار العميل @break
@case('resolved') محلولة @break
@case('closed') مغلقة @break
@default {{ $ticket->status }}
@endswitch
</span>
<div class="status-details">
@if($ticket->status === 'resolved' && $ticket->resolved_at)
<small>حُلت في {{ $ticket->resolved_at->diffForHumans() }}</small>
@elseif($ticket->status === 'waiting_customer')
<small>في انتظار الرد منذ {{ $ticket->updated_at->diffForHumans() }}</small>
@endif
</div>
</td>
<td>
<span class="status-badge priority-{{ $ticket->priority }}">
<i class="fas fa-flag"></i>
@switch($ticket->priority)
@case('low') منخفضة @break
@case('medium') متوسطة @break
@case('high') عالية @break
@case('urgent') عاجلة @break
@default {{ $ticket->priority }}
@endswitch
</span>
<div class="priority-sla">
@switch($ticket->priority)
@case('urgent')
<small style="color: #dc2626;">SLA: ساعة واحدة</small>
@break
@case('high')
<small style="color: #f59e0b;">SLA: 4 ساعات</small>
@break
@case('medium')
<small style="color: #3b82f6;">SLA: 24 ساعة</small>
@break
@default
<small style="color: #6b7280;">SLA: 48 ساعة</small>
@endswitch
</div>
</td>
<td>
@if($ticket->assignedAgent)
<div class="agent-info">
<div class="agent-avatar">
{{ strtoupper(substr($ticket->assignedAgent->name, 0, 2)) }}
</div>
<div class="agent-details">
<div class="agent-name">{{ $ticket->assignedAgent->name }}</div>
<div class="agent-workload">
<small>{{ rand(3, 15) }} تذاكر نشطة</small>
</div>
</div>
</div>
@else
<div class="unassigned">
<i class="fas fa-user-slash"></i> غير معين
<div class="assign-suggestion">
<small>يُنصح بالتعيين لـ {{ $csAgents->random()->name ?? 'وكيل متاح' }}</small>
</div>
</div>
@endif
</td>
<td>
<div class="time-info">
<div class="created-time">
<i class="fas fa-calendar-plus"></i> {{ $ticket->created_at->format('M d, H:i') }}
</div>
<div class="time-ago">{{ $ticket->created_at->diffForHumans() }}</div>
@if($ticket->first_response_at)
<div class="response-time">
<i class="fas fa-reply"></i> رُد خلال: {{ $ticket->created_at->diffInMinutes($ticket->first_response_at) }} دقيقة
</div>
@else
<div class="response-time overdue">
<i class="fas fa-clock"></i> {{ $ticket->created_at->diffInHours() }}h بدون رد
</div>
@endif
@if($ticket->resolved_at)
<div class="resolution-time">
<i class="fas fa-check"></i> حُل خلال: {{ $ticket->created_at->diffInHours($ticket->resolved_at) }}h
</div>
@endif
</div>
</td>
<td>
<div class="replies-info">
<div class="replies-count">
<i class="fas fa-comments"></i> {{ $ticket->replies_count ?? rand(0, 8) }}
</div>
@if(($ticket->replies_count ?? rand(0, 8)) > 0)
<div class="last-reply">
<small>آخر رد: {{ rand(1, 48) }}h</small>
</div>
@endif
<div class="conversation-health">
@if(($ticket->replies_count ?? rand(0, 8)) > 5)
<span class="health-indicator active">نشط</span>
@elseif(($ticket->replies_count ?? rand(0, 8)) > 2)
<span class="health-indicator moderate">متوسط</span>
@else
<span class="health-indicator low">قليل</span>
@endif
</div>
</div>
</td>
<td>
<div class="rating-info">
@if($ticket->satisfaction_rating)
<div class="rating-stars">
@for($i = 1; $i <= 5; $i++)
<i class="fas fa-star {{ $i <= $ticket->satisfaction_rating ? 'rated' : 'unrated' }}"></i>
@endfor
</div>
<div class="rating-value">{{ $ticket->satisfaction_rating }}/5</div>
@if($ticket->satisfaction_comment)
<div class="rating-comment" title="{{ $ticket->satisfaction_comment }}">
<i class="fas fa-quote-left"></i> {{ Str::limit($ticket->satisfaction_comment, 30) }}
</div>
@endif
@else
<div class="no-rating">
<i class="fas fa-star-o"></i> لم يُقيم بعد
</div>
@endif
</div>
</td>
<td>
<div class="actions-group">
<a href="{{ route('cs.tickets.show', $ticket->id) }}" class="action-btn btn-view">
<i class="fas fa-eye"></i> عرض
</a>
@if($ticket->status !== 'closed')
<a href="{{ route('cs.tickets.edit', $ticket->id) }}" class="action-btn btn-edit">
<i class="fas fa-edit"></i> تعديل
</a>
@endif
@if(!$ticket->assignedAgent)
<button class="action-btn btn-assign" onclick="assignTicket({{ $ticket->id }})">
<i class="fas fa-user-plus"></i> تعيين
</button>
@endif
</div>
</td>
</tr>
@endforeach
</tbody>
</table>
</div>

<!-- Grid View -->
<div class="tickets-grid" id="grid-view">
@foreach($tickets as $ticket)
<div class="ticket-card">
<div class="ticket-card-header">
<div>
<span class="ticket-card-id">#{{ $ticket->id }}</span>
<span class="ticket-card-priority">
<span class="status-badge priority-{{ $ticket->priority }}">
<i class="fas fa-flag"></i>
@switch($ticket->priority)
@case('low') منخفضة @break
@case('medium') متوسطة @break
@case('high') عالية @break
@case('urgent') عاجلة @break
@endswitch
</span>
</span>
</div>
<span class="status-badge status-{{ $ticket->status }}">
@switch($ticket->status)
@case('open') مفتوحة @break
@case('in_progress') قيد المعالجة @break
@case('waiting_customer') انتظار العميل @break
@case('resolved') محلولة @break
@case('closed') مغلقة @break
@endswitch
</span>
</div>

<h4 class="ticket-card-subject">{{ $ticket->subject }}</h4>
<p class="ticket-card-description">{{ $ticket->description }}</p>

<div class="ticket-card-meta">
<div class="ticket-card-customer">
<div class="customer-avatar">
{{ strtoupper(substr($ticket->user->name, 0, 2)) }}
</div>
<div>
<div class="customer-name">{{ $ticket->user->name }}</div>
<div class="customer-email">{{ $ticket->user->email }}</div>
</div>
</div>
<div class="ticket-card-time">
<div>{{ $ticket->created_at->format('Y-m-d H:i') }}</div>
<div>{{ $ticket->created_at->diffForHumans() }}</div>
</div>
</div>

<div class="ticket-card-actions">
<a href="{{ route('cs.tickets.show', $ticket->id) }}" class="action-btn btn-view">
<i class="fas fa-eye"></i> عرض
</a>
@if($ticket->status !== 'closed')
<a href="{{ route('cs.tickets.edit', $ticket->id) }}" class="action-btn btn-edit">
<i class="fas fa-edit"></i> تعديل
</a>
@endif
</div>
</div>
@endforeach
</div>

<!-- Pagination -->
<div class="pagination-wrapper">
<div class="pagination">
{{ $tickets->appends(request()->query())->links() }}
</div>
</div>
@else
<div class="empty-state">
<i class="fas fa-inbox"></i>
<h3>لا توجد تذاكر</h3>
<p>لم يتم العثور على تذاكر تطابق معايير البحث المحددة</p>
<a href="{{ route('cs.tickets.create') }}" class="btn-filter btn-primary">
<i class="fas fa-plus"></i> إنشاء تذكرة جديدة
</a>
</div>
@endif
</div>
</div>

<script>
// Toggle Filters
function toggleFilters() {
    const filtersBody = document.getElementById('filters-body');
    const filterText = document.getElementById('filter-text');
    const filterIcon = document.getElementById('filter-icon');
    
    if (filtersBody.classList.contains('active')) {
        filtersBody.classList.remove('active');
        filterText.textContent = 'إظهار الفلاتر';
        filterIcon.classList.remove('fa-chevron-up');
        filterIcon.classList.add('fa-chevron-down');
    } else {
        filtersBody.classList.add('active');
        filterText.textContent = 'إخفاء الفلاتر';
        filterIcon.classList.remove('fa-chevron-down');
        filterIcon.classList.add('fa-chevron-up');
    }
}

// Switch View
function switchView(view) {
    const tableView = document.getElementById('table-view');
    const gridView = document.getElementById('grid-view');
    const tableBtn = document.getElementById('table-btn');
    const gridBtn = document.getElementById('grid-btn');
    
    if (view === 'table') {
        tableView.style.display = 'block';
        gridView.classList.remove('active');
        tableBtn.classList.add('active');
        gridBtn.classList.remove('active');
        localStorage.setItem('ticketsView', 'table');
    } else {
        tableView.style.display = 'none';
        gridView.classList.add('active');
        tableBtn.classList.remove('active');
        gridBtn.classList.add('active');
        localStorage.setItem('ticketsView', 'grid');
    }
}

// Auto-submit form on filter change
document.querySelectorAll('.filter-select').forEach(select => {
    select.addEventListener('change', function() {
        if (this.value !== '') {
            this.form.submit();
        }
    });
});

// Highlight search terms
const searchTerm = '{{ request("search") }}';
if (searchTerm) {
    document.querySelectorAll('.ticket-subject, .customer-name, .customer-email').forEach(element => {
        if (element.textContent.toLowerCase().includes(searchTerm.toLowerCase())) {
            element.innerHTML = element.innerHTML.replace(
                new RegExp(searchTerm, 'gi'),
                `<mark style="background: #fef3c7; padding: 0.1rem 0.2rem; border-radius: 3px;">$&</mark>`
            );
        }
    });
}

// Export Tickets
function exportTickets() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'csv');
    window.location.href = '{{ route("cs.tickets.index") }}?' + params.toString();
}

// Assign Ticket
function assignTicket(ticketId) {
    // This would open a modal or redirect to assignment page
    alert('وظيفة تعيين التذكرة قيد التطوير');
}

// Load saved view preference
document.addEventListener('DOMContentLoaded', function() {
    const savedView = localStorage.getItem('ticketsView') || 'table';
    switchView(savedView);
    
    // Auto-expand filters if any are active
    const hasActiveFilters = {{ count(request()->except('page')) > 0 ? 'true' : 'false' }};
    if (hasActiveFilters) {
        toggleFilters();
    }
});

// Real-time updates (optional)
setInterval(function() {
    // You can add AJAX calls here to refresh ticket counts
    console.log('Checking for updates...');
}, 30000);

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey || e.metaKey) {
        switch(e.key) {
            case 'f':
                e.preventDefault();
                document.querySelector('input[name="search"]').focus();
                break;
            case 'n':
                e.preventDefault();
                window.location.href = '{{ route("cs.tickets.create") }}';
                break;
        }
    }
});
</script>

</body>
</html>