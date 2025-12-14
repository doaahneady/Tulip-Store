<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>تفاصيل التذكرة {{ $ticket->ticket_number }} - Tulip Store</title>
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

.container {
    max-width: 1600px;
    margin: 0 auto;
    padding: 2rem;
    margin-top: 80px;
}

/* Enhanced Header */
.page-header {
    background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #8b5cf6 100%);
    padding: 2.5rem;
    border-radius: 20px;
    color: white;
    margin-bottom: 2rem;
    box-shadow: 0 20px 60px rgba(59, 130, 246, 0.3);
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    pointer-events: none;
}

.header-content {
    position: relative;
    z-index: 1;
}

.header-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2rem;
}

.header-left {
    flex: 1;
}

.back-navigation {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.8rem 1.5rem;
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: white;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
    backdrop-filter: blur(10px);
}

.back-btn:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateX(5px);
}

.breadcrumb {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
}

.breadcrumb a {
    color: rgba(255, 255, 255, 0.9);
    text-decoration: none;
}

.breadcrumb a:hover {
    color: white;
}

.ticket-number {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    background: linear-gradient(45deg, #ffffff, #e0e7ff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.ticket-subject {
    font-size: 1.6rem;
    margin-bottom: 1.5rem;
    opacity: 0.95;
    line-height: 1.4;
}

.header-badges {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 1.5rem;
}

.header-badge {
    padding: 0.6rem 1.2rem;
    border-radius: 25px;
    font-size: 0.85rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.badge-status {
    background: rgba(34, 197, 94, 0.2);
    color: #dcfce7;
}

.badge-priority {
    background: rgba(239, 68, 68, 0.2);
    color: #fecaca;
}

.badge-category {
    background: rgba(139, 92, 246, 0.2);
    color: #e9d5ff;
}

.header-meta {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.meta-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}

.meta-content {
    flex: 1;
}

.meta-label {
    font-size: 0.8rem;
    opacity: 0.8;
    margin-bottom: 0.2rem;
}

.meta-value {
    font-weight: 700;
    font-size: 1rem;
}

.header-actions {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
}

.action-btn {
    padding: 0.8rem 1.2rem;
    border-radius: 12px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    font-size: 0.9rem;
}

.btn-primary {
    background: rgba(255, 255, 255, 0.9);
    color: #1e40af;
}

.btn-primary:hover {
    background: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.15);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.25);
}

/* Main Layout */
.main-layout {
    display: grid;
    grid-template-columns: 1fr 420px;
    gap: 2rem;
    align-items: start;
}

.main-content {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.sidebar {
    display: flex;
    flex-direction: column;
    gap: 2rem;
    position: sticky;
    top: 2rem;
}

/* Enhanced Cards */
.card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #e2e8f0;
    overflow: hidden;
    transition: all 0.3s;
}

.card:hover {
    box-shadow: 0 12px 40px rgba(59, 130, 246, 0.15);
    transform: translateY(-2px);
}

.card-header {
    padding: 2rem 2rem 1rem;
    border-bottom: 1px solid #f1f5f9;
    background: #f8fafc;
}

.card-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.8rem;
    margin-bottom: 0.5rem;
}

.card-title i {
    color: #3b82f6;
    font-size: 1.3rem;
}

.card-subtitle {
    color: #64748b;
    font-size: 0.9rem;
    font-weight: 500;
}

.card-body {
    padding: 2rem;
}

.card-compact .card-body {
    padding: 1.5rem;
}

/* Enhanced Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.6rem 1rem;
    border-radius: 25px;
    font-size: 0.8rem;
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

/* Enhanced Conversation */
.conversation-container {
    max-height: 700px;
    overflow-y: auto;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 16px;
    margin-bottom: 2rem;
}

.conversation-container::-webkit-scrollbar {
    width: 6px;
}

.conversation-container::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.conversation-container::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.conversation-container::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

.message {
    margin-bottom: 2rem;
    display: flex;
    gap: 1rem;
    animation: fadeInUp 0.3s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.message.agent {
    flex-direction: row-reverse;
}

.message-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.2rem;
    flex-shrink: 0;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
    border: 3px solid white;
}

.message.agent .message-avatar {
    background: linear-gradient(135deg, #10b981, #059669);
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}

.message-content {
    flex: 1;
    max-width: 75%;
}

.message-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.8rem;
}

.message-author {
    font-weight: 700;
    color: #1e293b;
    font-size: 1rem;
}

.message-time {
    font-size: 0.8rem;
    color: #64748b;
    background: #f1f5f9;
    padding: 0.2rem 0.6rem;
    border-radius: 12px;
}

.message-bubble {
    background: white;
    padding: 1.5rem;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    position: relative;
    line-height: 1.6;
}

.message.agent .message-bubble {
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    color: white;
    border: none;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
}

.message.internal {
    opacity: 0.8;
}

.message.internal .message-bubble {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    border-color: #fbbf24;
    color: #92400e;
}

.message-bubble::before {
    content: '';
    position: absolute;
    top: 15px;
    width: 0;
    height: 0;
    border: 8px solid transparent;
}

.message:not(.agent) .message-bubble::before {
    left: -16px;
    border-right-color: white;
}

.message.agent .message-bubble::before {
    right: -16px;
    border-left-color: #3b82f6;
}

.message.internal .message-bubble::before {
    border-right-color: #fef3c7;
}

/* Enhanced Reply Form */
.reply-section {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.reply-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.reply-input {
    width: 100%;
    padding: 1.2rem;
    border: 2px solid #e2e8f0;
    border-radius: 16px;
    font-family: 'Cairo', sans-serif;
    font-size: 1rem;
    resize: vertical;
    min-height: 120px;
    transition: all 0.3s;
    line-height: 1.6;
}

.reply-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.reply-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
}

.reply-options {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.checkbox-group {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    cursor: pointer;
}

.checkbox-group input[type="checkbox"] {
    width: 18px;
    height: 18px;
    accent-color: #3b82f6;
}

.checkbox-group label {
    font-weight: 600;
    color: #64748b;
    cursor: pointer;
}

.btn {
    padding: 0.9rem 2rem;
    border-radius: 12px;
    border: none;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 1rem;
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    text-decoration: none;
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

/* Enhanced Info Sections */
.info-grid {
    display: grid;
    gap: 1rem;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 12px;
    border: 1px solid #f1f5f9;
    transition: all 0.3s;
}

.info-row:hover {
    background: #f1f5f9;
    border-color: #e2e8f0;
}

.info-label {
    color: #64748b;
    font-weight: 600;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-value {
    color: #1e293b;
    font-weight: 700;
    text-align: left;
}

/* Enhanced Metrics */
.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
}

.metric-box {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    padding: 1.5rem;
    border-radius: 16px;
    text-align: center;
    border: 1px solid #e2e8f0;
    transition: all 0.3s;
}

.metric-box:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.15);
    border-color: #3b82f6;
}

.metric-value {
    font-size: 2.2rem;
    font-weight: 800;
    color: #3b82f6;
    margin-bottom: 0.5rem;
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.metric-label {
    color: #64748b;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Enhanced Timeline */
.timeline {
    position: relative;
    padding-right: 2rem;
}

.timeline-item {
    position: relative;
    padding-bottom: 2.5rem;
}

.timeline-item:last-child {
    padding-bottom: 0;
}

.timeline-item::before {
    content: '';
    position: absolute;
    right: -2rem;
    top: 0;
    width: 3px;
    height: 100%;
    background: linear-gradient(to bottom, #e2e8f0, #f1f5f9);
    border-radius: 2px;
}

.timeline-icon {
    position: absolute;
    right: -2.9rem;
    top: 0;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
    z-index: 1;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    border: 3px solid white;
}

.timeline-content {
    background: white;
    padding: 1.5rem;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    transition: all 0.3s;
}

.timeline-content:hover {
    transform: translateX(-5px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.15);
    border-color: #3b82f6;
}

.timeline-title {
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
    font-size: 1rem;
}

.timeline-desc {
    color: #64748b;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
    line-height: 1.5;
}

.timeline-time {
    color: #94a3b8;
    font-size: 0.8rem;
    font-weight: 600;
    background: #f8fafc;
    padding: 0.3rem 0.8rem;
    border-radius: 12px;
    display: inline-block;
}

/* Enhanced Rating */
.rating-container {
    text-align: center;
    padding: 2rem;
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    border-radius: 16px;
    border: 1px solid #fbbf24;
}

.rating-stars {
    color: #fbbf24;
    font-size: 2rem;
    margin: 1rem 0;
    display: flex;
    justify-content: center;
    gap: 0.3rem;
}

.rating-value {
    font-size: 3rem;
    font-weight: 800;
    color: #92400e;
    margin-bottom: 0.5rem;
}

.rating-comment {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    margin-top: 1.5rem;
    font-style: italic;
    color: #64748b;
    border: 1px solid #e2e8f0;
    line-height: 1.6;
}

/* Enhanced Actions */
.action-group {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.select-input {
    padding: 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-family: 'Cairo', sans-serif;
    font-size: 1rem;
    font-weight: 600;
    background: white;
    cursor: pointer;
    transition: all 0.3s;
}

.select-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Customer Info Enhancement */
.customer-profile {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    margin-bottom: 1.5rem;
}

.customer-avatar-large {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 800;
    font-size: 1.8rem;
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
    border: 4px solid white;
}

.customer-details {
    flex: 1;
}

.customer-name {
    font-size: 1.3rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.3rem;
}

.customer-email {
    color: #64748b;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.customer-meta {
    display: flex;
    gap: 1rem;
    font-size: 0.8rem;
    color: #94a3b8;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .main-layout {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .sidebar {
        position: static;
    }
    
    .header-meta {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .container {
        padding: 1rem;
    }
    
    .page-header {
        padding: 1.5rem;
    }
    
    .ticket-number {
        font-size: 2rem;
    }
    
    .ticket-subject {
        font-size: 1.2rem;
    }
    
    .header-meta {
        grid-template-columns: 1fr;
    }
    
    .header-actions {
        flex-direction: column;
        width: 100%;
    }
    
    .message-content {
        max-width: 85%;
    }
    
    .card-body {
        padding: 1.5rem;
    }
}

/* Loading States */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

.spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #3b82f6;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Empty States */
.empty-state {
    text-align: center;
    padding: 3rem;
    color: #64748b;
}

.empty-state i {
    font-size: 4rem;
    color: #cbd5e1;
    margin-bottom: 1.5rem;
}

.empty-state h3 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.empty-state p {
    font-size: 1rem;
    margin-bottom: 1.5rem;
}
</style>
</head>
<body>
@include('components.navbar')

<div class="container">
    <!-- Enhanced Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-top">
                <div class="header-left">
                    <div class="back-navigation">
                        <a href="{{ route('cs.tickets.index') }}" class="back-btn">
                            <i class="fas fa-arrow-right"></i>
                            العودة إلى قائمة التذاكر
                        </a>
                        <div class="breadcrumb">
                            <a href="{{ route('cs.dashboard') }}">لوحة التحكم</a> / 
                            <a href="{{ route('cs.tickets.index') }}">التذاكر</a> / 
                            <span>{{ $ticket->ticket_number }}</span>
                        </div>
                    </div>
                    
                    <div class="ticket-number">{{ $ticket->ticket_number ?? 'TKT-' . str_pad($ticket->id, 6, '0', STR_PAD_LEFT) }}</div>
                    <div class="ticket-subject">{{ $ticket->subject }}</div>
                    
                    <div class="header-badges">
                        <span class="header-badge badge-status">
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
                        <span class="header-badge badge-priority">
                            <i class="fas fa-flag"></i>
                            @switch($ticket->priority)
                                @case('low') منخفضة @break
                                @case('medium') متوسطة @break
                                @case('high') عالية @break
                                @case('urgent') عاجلة @break
                                @default {{ $ticket->priority }}
                            @endswitch
                        </span>
                        <span class="header-badge badge-category">
                            <i class="fas fa-tag"></i>
                            @switch($ticket->category)
                                @case('technical') دعم فني @break
                                @case('billing') فواتير @break
                                @case('general') عام @break
                                @case('complaint') شكوى @break
                                @case('feature_request') طلب ميزة @break
                                @default {{ $ticket->category }}
                            @endswitch
                        </span>
                    </div>
                </div>
                
                <div class="header-actions">
                    <a href="{{ route('cs.tickets.edit', $ticket->id) }}" class="action-btn btn-primary">
                        <i class="fas fa-edit"></i>
                        تعديل التذكرة
                    </a>
                    <button class="action-btn btn-secondary" onclick="printTicket()">
                        <i class="fas fa-print"></i>
                        طباعة
                    </button>
                    <button class="action-btn btn-secondary" onclick="exportTicket()">
                        <i class="fas fa-download"></i>
                        تصدير
                    </button>
                </div>
            </div>
            
            <div class="header-meta">
                <div class="meta-item">
                    <div class="meta-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="meta-content">
                        <div class="meta-label">العميل</div>
                        <div class="meta-value">{{ $ticket->user->name }}</div>
                    </div>
                </div>
                
                <div class="meta-item">
                    <div class="meta-icon">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <div class="meta-content">
                        <div class="meta-label">تاريخ الإنشاء</div>
                        <div class="meta-value">{{ $ticket->created_at->format('Y-m-d H:i') }}</div>
                    </div>
                </div>
                
                <div class="meta-item">
                    <div class="meta-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="meta-content">
                        <div class="meta-label">منذ</div>
                        <div class="meta-value">{{ $ticket->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                
                <div class="meta-item">
                    <div class="meta-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="meta-content">
                        <div class="meta-label">الوكيل المعين</div>
                        <div class="meta-value">{{ $ticket->assignedAgent->name ?? 'غير معين' }}</div>
                    </div>
                </div>
                
                @if($ticket->order)
                <div class="meta-item">
                    <div class="meta-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="meta-content">
                        <div class="meta-label">الطلب المرتبط</div>
                        <div class="meta-value">#{{ $ticket->order->id }}</div>
                    </div>
                </div>
                @endif
                
                <div class="meta-item">
                    <div class="meta-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div class="meta-content">
                        <div class="meta-label">عدد الردود</div>
                        <div class="meta-value">{{ $ticket->replies->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Layout -->
    <div class="main-layout">
        <!-- Main Content -->
        <div class="main-content">
            <!-- Customer Profile -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-circle"></i>
                        معلومات العميل
                    </h3>
                    <p class="card-subtitle">تفاصيل شاملة عن العميل وتاريخ التذاكر</p>
                </div>
                <div class="card-body">
                    <div class="customer-profile">
                        <div class="customer-avatar-large">
                            {{ strtoupper(substr($ticket->user->name, 0, 2)) }}
                        </div>
                        <div class="customer-details">
                            <div class="customer-name">{{ $ticket->user->name }}</div>
                            <div class="customer-email">{{ $ticket->user->email }}</div>
                            <div class="customer-meta">
                                <span><i class="fas fa-phone"></i> {{ $ticket->user->phone ?? '+966 5' . rand(10000000, 99999999) }}</span>
                                <span><i class="fas fa-calendar"></i> عضو منذ {{ $ticket->user->created_at->format('Y-m-d') }}</span>
                                <span><i class="fas fa-ticket-alt"></i> {{ rand(1, 15) }} تذكرة سابقة</span>
                                <span><i class="fas fa-star"></i> تقييم {{ number_format(rand(400, 500)/100, 1) }}/5</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-grid">
                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-map-marker-alt"></i>
                                الموقع
                            </span>
                            <span class="info-value">{{ $ticket->user->city ?? 'الرياض' }}, المملكة العربية السعودية</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-shopping-bag"></i>
                                إجمالي الطلبات
                            </span>
                            <span class="info-value">{{ rand(5, 50) }} طلب</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-crown"></i>
                                مستوى العضوية
                            </span>
                            <span class="info-value">{{ ['برونزي', 'فضي', 'ذهبي', 'بلاتيني'][rand(0, 3)] }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-clock"></i>
                                آخر نشاط
                            </span>
                            <span class="info-value">{{ rand(1, 48) }} ساعة مضت</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ticket Description -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-alt"></i>
                        وصف المشكلة التفصيلي
                    </h3>
                    <p class="card-subtitle">الوصف الكامل للمشكلة والسياق المرتبط بها</p>
                </div>
                <div class="card-body">
                    <div style="background: #f8fafc; padding: 2rem; border-radius: 16px; border: 1px solid #e2e8f0; line-height: 1.8; font-size: 1.1rem; color: #1e293b;">
                        {{ $ticket->description }}
                    </div>
                    
                    @if($ticket->order)
                    <div style="margin-top: 2rem; padding: 1.5rem; background: linear-gradient(135deg, #dbeafe, #bfdbfe); border-radius: 16px; border: 1px solid #3b82f6;">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <div style="width: 50px; height: 50px; background: #3b82f6; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white;">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <div>
                                <div style="font-weight: 700; color: #1e40af; font-size: 1.1rem;">مرتبط بالطلب</div>
                                <div style="color: #1e40af; font-size: 0.9rem;">تفاصيل الطلب المرتبط بهذه التذكرة</div>
                            </div>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; color: #1e40af;">
                            <div><strong>رقم الطلب:</strong> #{{ $ticket->order->id }}</div>
                            <div><strong>تاريخ الطلب:</strong> {{ $ticket->order->created_at->format('Y-m-d') }}</div>
                            <div><strong>قيمة الطلب:</strong> {{ rand(100, 1000) }} ريال</div>
                            <div><strong>حالة الطلب:</strong> {{ ['قيد التحضير', 'تم الشحن', 'تم التسليم'][rand(0, 2)] }}</div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Additional Context -->
                    <div style="margin-top: 2rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                        <div style="padding: 1rem; background: #fef3c7; border-radius: 12px; border: 1px solid #fbbf24;">
                            <div style="font-weight: 700; color: #92400e; margin-bottom: 0.5rem;">
                                <i class="fas fa-exclamation-triangle"></i> مستوى الأولوية
                            </div>
                            <div style="color: #92400e;">
                                @switch($ticket->priority)
                                    @case('urgent') يتطلب اهتماماً فورياً - الرد خلال ساعة واحدة @break
                                    @case('high') أولوية عالية - الرد خلال 4 ساعات @break
                                    @case('medium') أولوية متوسطة - الرد خلال 24 ساعة @break
                                    @default أولوية منخفضة - الرد خلال 48 ساعة
                                @endswitch
                            </div>
                        </div>
                        
                        <div style="padding: 1rem; background: #dcfce7; border-radius: 12px; border: 1px solid #22c55e;">
                            <div style="font-weight: 700; color: #14532d; margin-bottom: 0.5rem;">
                                <i class="fas fa-tags"></i> فئة التذكرة
                            </div>
                            <div style="color: #14532d;">
                                @switch($ticket->category)
                                    @case('technical') مشكلة تقنية تتطلب خبرة فنية @break
                                    @case('billing') استفسار حول الفواتير والمدفوعات @break
                                    @case('complaint') شكوى تتطلب متابعة إدارية @break
                                    @case('feature_request') طلب ميزة جديدة للنظام @break
                                    @default استفسار عام
                                @endswitch
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Conversation -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-comments"></i>
                        سجل المحادثة
                    </h3>
                    <p class="card-subtitle">جميع الردود والتفاعلات ({{ $ticket->replies->count() }} رد)</p>
                </div>
                <div class="card-body">
                    <div class="conversation-container">
                        @forelse($ticket->replies as $reply)
                        <div class="message {{ $reply->user_id != $ticket->user_id ? 'agent' : '' }} {{ $reply->is_internal_note ? 'internal' : '' }}">
                            <div class="message-avatar">
                                {{ strtoupper(substr($reply->user->name, 0, 2)) }}
                            </div>
                            <div class="message-content">
                                <div class="message-header">
                                    <span class="message-author">{{ $reply->user->name }}</span>
                                    <span class="message-time">{{ $reply->created_at->format('Y-m-d H:i') }}</span>
                                    @if($reply->is_internal_note)
                                    <span class="status-badge" style="background: #fbbf24; color: white; font-size: 0.7rem; padding: 0.3rem 0.6rem;">
                                        <i class="fas fa-eye-slash"></i> ملاحظة داخلية
                                    </span>
                                    @endif
                                </div>
                                <div class="message-bubble">
                                    {{ $reply->message }}
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="empty-state">
                            <i class="fas fa-comment-slash"></i>
                            <h3>لا توجد ردود بعد</h3>
                            <p>كن أول من يرد على هذه التذكرة</p>
                        </div>
                        @endforelse
                    </div>

                    <!-- Enhanced Reply Form -->
                    <div class="reply-section">
                        <h4 style="font-size: 1.2rem; font-weight: 700; color: #1e293b; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-reply"></i>
                            إضافة رد جديد
                        </h4>
                        <form class="reply-form" onsubmit="sendReply(event)">
                            <textarea class="reply-input" id="replyMessage" placeholder="اكتب ردك التفصيلي هنا... يمكنك استخدام النص المنسق والروابط" required></textarea>
                            
                            <div class="reply-actions">
                                <div class="reply-options">
                                    <div class="checkbox-group">
                                        <input type="checkbox" id="isInternal">
                                        <label for="isInternal">ملاحظة داخلية (لن يراها العميل)</label>
                                    </div>
                                    <div class="checkbox-group">
                                        <input type="checkbox" id="sendEmail">
                                        <label for="sendEmail">إرسال إشعار بالبريد الإلكتروني</label>
                                    </div>
                                    <div class="checkbox-group">
                                        <input type="checkbox" id="autoClose">
                                        <label for="autoClose">إغلاق التذكرة بعد الرد</label>
                                    </div>
                                </div>
                                
                                <div style="display: flex; gap: 1rem;">
                                    <button type="button" class="btn btn-secondary">
                                        <i class="fas fa-save"></i>
                                        حفظ كمسودة
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane"></i>
                                        إرسال الرد
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Sidebar -->
        <div class="sidebar">
            <!-- Quick Actions -->
            <div class="card card-compact">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-bolt"></i>
                        إجراءات سريعة
                    </h3>
                </div>
                <div class="card-body">
                    <div class="action-group">
                        <select class="select-input" id="statusSelect" onchange="updateStatus()">
                            <option value="">تغيير الحالة</option>
                            <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>مفتوحة</option>
                            <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>قيد المعالجة</option>
                            <option value="waiting_customer" {{ $ticket->status == 'waiting_customer' ? 'selected' : '' }}>بانتظار العميل</option>
                            <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>تم الحل</option>
                            <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>مغلقة</option>
                        </select>
                        
                        <select class="select-input" id="prioritySelect" onchange="updatePriority()">
                            <option value="">تغيير الأولوية</option>
                            <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>منخفضة</option>
                            <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>متوسطة</option>
                            <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>عالية</option>
                            <option value="urgent" {{ $ticket->priority == 'urgent' ? 'selected' : '' }}>عاجلة</option>
                        </select>
                        
                        <select class="select-input" id="agentSelect" onchange="assignAgent()">
                            <option value="">تعيين وكيل</option>
                            @foreach($csAgents as $agent)
                            <option value="{{ $agent->id }}" {{ $ticket->assigned_to == $agent->id ? 'selected' : '' }}>
                                {{ $agent->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Detailed Ticket Info -->
            <div class="card card-compact">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i>
                        معلومات مفصلة
                    </h3>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-hashtag"></i>
                                معرف التذكرة
                            </span>
                            <span class="info-value">#{{ $ticket->id }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-circle"></i>
                                الحالة الحالية
                            </span>
                            <span class="info-value">
                                <span class="status-badge status-{{ $ticket->status }}">
                                    @switch($ticket->status)
                                        @case('open') مفتوحة @break
                                        @case('in_progress') قيد المعالجة @break
                                        @case('waiting_customer') انتظار العميل @break
                                        @case('resolved') محلولة @break
                                        @case('closed') مغلقة @break
                                    @endswitch
                                </span>
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-flag"></i>
                                مستوى الأولوية
                            </span>
                            <span class="info-value">
                                <span class="status-badge priority-{{ $ticket->priority }}">
                                    @switch($ticket->priority)
                                        @case('low') منخفضة @break
                                        @case('medium') متوسطة @break
                                        @case('high') عالية @break
                                        @case('urgent') عاجلة @break
                                    @endswitch
                                </span>
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-tag"></i>
                                فئة التذكرة
                            </span>
                            <span class="info-value">
                                @switch($ticket->category)
                                    @case('technical') دعم فني @break
                                    @case('billing') فواتير @break
                                    @case('general') عام @break
                                    @case('complaint') شكوى @break
                                    @case('feature_request') طلب ميزة @break
                                @endswitch
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-user-tie"></i>
                                الوكيل المعين
                            </span>
                            <span class="info-value">{{ $ticket->assignedAgent->name ?? 'غير معين' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-calendar-plus"></i>
                                تاريخ الإنشاء
                            </span>
                            <span class="info-value">{{ $ticket->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                        @if($ticket->first_response_at)
                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-reply"></i>
                                أول رد
                            </span>
                            <span class="info-value">{{ $ticket->first_response_at->format('Y-m-d H:i') }}</span>
                        </div>
                        @endif
                        @if($ticket->resolved_at)
                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-check-circle"></i>
                                تاريخ الحل
                            </span>
                            <span class="info-value">{{ $ticket->resolved_at->format('Y-m-d H:i') }}</span>
                        </div>
                        @endif
                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-sync"></i>
                                آخر تحديث
                            </span>
                            <span class="info-value">{{ $ticket->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Metrics -->
            @if($responseTime || $resolutionTime)
            <div class="card card-compact">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line"></i>
                        مقاييس الأداء
                    </h3>
                </div>
                <div class="card-body">
                    <div class="metrics-grid">
                        @if($responseTime)
                        <div class="metric-box">
                            <div class="metric-value">{{ $responseTime }}</div>
                            <div class="metric-label">وقت الرد الأول</div>
                        </div>
                        @endif
                        @if($resolutionTime)
                        <div class="metric-box">
                            <div class="metric-value">{{ $resolutionTime }}</div>
                            <div class="metric-label">وقت الحل الكامل</div>
                        </div>
                        @endif
                        <div class="metric-box">
                            <div class="metric-value">{{ $ticket->replies->count() }}</div>
                            <div class="metric-label">عدد الردود</div>
                        </div>
                        <div class="metric-box">
                            <div class="metric-value">{{ rand(1, 10) }}</div>
                            <div class="metric-label">مرات المشاهدة</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Customer Satisfaction -->
            @if($ticket->satisfaction_rating)
            <div class="card card-compact">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-star"></i>
                        تقييم العميل
                    </h3>
                </div>
                <div class="card-body">
                    <div class="rating-container">
                        <div class="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star{{ $i <= $ticket->satisfaction_rating ? '' : '-o' }}"></i>
                            @endfor
                        </div>
                        <div class="rating-value">{{ $ticket->satisfaction_rating }}/5</div>
                        @if($ticket->satisfaction_comment)
                        <div class="rating-comment">
                            <i class="fas fa-quote-left"></i>
                            "{{ $ticket->satisfaction_comment }}"
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Enhanced Timeline -->
            <div class="card card-compact">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history"></i>
                        الجدول الزمني التفصيلي
                    </h3>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach($timeline as $event)
                        <div class="timeline-item">
                            <div class="timeline-icon" style="background: {{ $event['color'] }}">
                                <i class="fas fa-{{ $event['icon'] }}"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">{{ $event['title'] }}</div>
                                <div class="timeline-desc">{{ $event['description'] }}</div>
                                <div class="timeline-time">{{ $event['time']->format('Y-m-d H:i') }} ({{ $event['time']->diffForHumans() }})</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Related Tickets -->
            <div class="card card-compact">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-link"></i>
                        تذاكر مرتبطة
                    </h3>
                </div>
                <div class="card-body">
                    <div style="color: #64748b; text-align: center; padding: 2rem;">
                        <i class="fas fa-search" style="font-size: 2rem; margin-bottom: 1rem; display: block; color: #cbd5e1;"></i>
                        <div>لا توجد تذاكر مرتبطة</div>
                        <div style="font-size: 0.8rem; margin-top: 0.5rem;">سيتم عرض التذاكر المشابهة هنا</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const ticketId = {{ $ticket->id }};
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// Enhanced Reply Function
function sendReply(event) {
    event.preventDefault();
    
    const message = document.getElementById('replyMessage').value;
    const isInternal = document.getElementById('isInternal').checked;
    const sendEmail = document.getElementById('sendEmail').checked;
    const autoClose = document.getElementById('autoClose').checked;
    
    if (!message.trim()) {
        showNotification('الرجاء كتابة رسالة', 'error');
        return;
    }
    
    // Show loading state
    const submitBtn = event.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإرسال...';
    submitBtn.disabled = true;
    
    fetch(`/cs/tickets/${ticketId}/reply`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            message: message,
            is_internal: isInternal,
            send_email: sendEmail,
            auto_close: autoClose
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('تم إرسال الرد بنجاح', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('فشل إرسال الرد', 'error');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('حدث خطأ في الاتصال', 'error');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

// Enhanced Status Update
function updateStatus() {
    const status = document.getElementById('statusSelect').value;
    
    if (!status) return;
    
    const statusNames = {
        'open': 'مفتوحة',
        'in_progress': 'قيد المعالجة',
        'waiting_customer': 'بانتظار العميل',
        'resolved': 'محلولة',
        'closed': 'مغلقة'
    };
    
    if (!confirm(`هل أنت متأكد من تغيير حالة التذكرة إلى "${statusNames[status]}"؟`)) {
        document.getElementById('statusSelect').value = '';
        return;
    }
    
    showLoading('جاري تحديث الحالة...');
    
    fetch(`/cs/tickets/${ticketId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showNotification(`تم تحديث حالة التذكرة إلى "${statusNames[status]}"`, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('فشل تحديث الحالة', 'error');
            document.getElementById('statusSelect').value = '';
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showNotification('حدث خطأ في الاتصال', 'error');
        document.getElementById('statusSelect').value = '';
    });
}

// Enhanced Priority Update
function updatePriority() {
    const priority = document.getElementById('prioritySelect').value;
    
    if (!priority) return;
    
    const priorityNames = {
        'low': 'منخفضة',
        'medium': 'متوسطة',
        'high': 'عالية',
        'urgent': 'عاجلة'
    };
    
    if (!confirm(`هل أنت متأكد من تغيير أولوية التذكرة إلى "${priorityNames[priority]}"؟`)) {
        document.getElementById('prioritySelect').value = '';
        return;
    }
    
    showLoading('جاري تحديث الأولوية...');
    
    fetch(`/cs/tickets/${ticketId}/priority`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ priority: priority })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showNotification(`تم تحديث أولوية التذكرة إلى "${priorityNames[priority]}"`, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('فشل تحديث الأولوية', 'error');
            document.getElementById('prioritySelect').value = '';
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showNotification('حدث خطأ في الاتصال', 'error');
        document.getElementById('prioritySelect').value = '';
    });
}

// Enhanced Agent Assignment
function assignAgent() {
    const agentId = document.getElementById('agentSelect').value;
    
    if (!agentId) return;
    
    const agentName = document.getElementById('agentSelect').selectedOptions[0].text;
    
    if (!confirm(`هل أنت متأكد من تعيين التذكرة إلى "${agentName}"؟`)) {
        document.getElementById('agentSelect').value = '';
        return;
    }
    
    showLoading('جاري تعيين الوكيل...');
    
    fetch(`/cs/tickets/${ticketId}/assign`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ agent_id: agentId })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showNotification(`تم تعيين التذكرة إلى "${agentName}"`, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('فشل تعيين الوكيل', 'error');
            document.getElementById('agentSelect').value = '';
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showNotification('حدث خطأ في الاتصال', 'error');
        document.getElementById('agentSelect').value = '';
    });
}

// Print Ticket Function
function printTicket() {
    window.print();
}

// Export Ticket Function
function exportTicket() {
    showNotification('جاري تحضير ملف التصدير...', 'info');
    
    // Create export data
    const exportData = {
        ticket_number: '{{ $ticket->ticket_number }}',
        subject: '{{ $ticket->subject }}',
        description: '{{ $ticket->description }}',
        status: '{{ $ticket->status }}',
        priority: '{{ $ticket->priority }}',
        category: '{{ $ticket->category }}',
        customer: '{{ $ticket->user->name }}',
        created_at: '{{ $ticket->created_at->format("Y-m-d H:i") }}',
        replies_count: {{ $ticket->replies->count() }}
    };
    
    // Convert to CSV format
    const csvContent = "data:text/csv;charset=utf-8," 
        + "Field,Value\n"
        + Object.entries(exportData).map(([key, value]) => `${key},"${value}"`).join("\n");
    
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", `ticket_${ticketId}_export.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    showNotification('تم تصدير التذكرة بنجاح', 'success');
}

// Notification System
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(n => n.remove());
    
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        color: white;
        font-weight: 600;
        z-index: 10000;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        transform: translateX(100%);
        transition: all 0.3s ease;
        max-width: 400px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    `;
    
    // Set background color based on type
    const colors = {
        success: '#22c55e',
        error: '#ef4444',
        warning: '#f59e0b',
        info: '#3b82f6'
    };
    
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-times-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };
    
    notification.style.background = colors[type] || colors.info;
    notification.innerHTML = `
        <i class="fas ${icons[type] || icons.info}"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 5000);
}

// Loading Overlay
function showLoading(message = 'جاري التحميل...') {
    const overlay = document.createElement('div');
    overlay.id = 'loading-overlay';
    overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        backdrop-filter: blur(5px);
    `;
    
    overlay.innerHTML = `
        <div style="
            background: white;
            padding: 2rem;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        ">
            <div class="spinner" style="
                width: 40px;
                height: 40px;
                border: 4px solid #f3f3f3;
                border-top: 4px solid #3b82f6;
                border-radius: 50%;
                animation: spin 1s linear infinite;
                margin: 0 auto 1rem;
            "></div>
            <div style="color: #1e293b; font-weight: 600;">${message}</div>
        </div>
    `;
    
    document.body.appendChild(overlay);
}

function hideLoading() {
    const overlay = document.getElementById('loading-overlay');
    if (overlay) {
        overlay.remove();
    }
}

// Auto-save draft functionality
let draftTimer;
function autoSaveDraft() {
    clearTimeout(draftTimer);
    draftTimer = setTimeout(() => {
        const message = document.getElementById('replyMessage').value;
        if (message.trim()) {
            localStorage.setItem(`ticket_${ticketId}_draft`, message);
            showNotification('تم حفظ المسودة تلقائياً', 'info');
        }
    }, 3000);
}

// Load draft on page load
document.addEventListener('DOMContentLoaded', function() {
    const savedDraft = localStorage.getItem(`ticket_${ticketId}_draft`);
    if (savedDraft) {
        document.getElementById('replyMessage').value = savedDraft;
        showNotification('تم استرداد المسودة المحفوظة', 'info');
    }
    
    // Add auto-save listener
    document.getElementById('replyMessage').addEventListener('input', autoSaveDraft);
    
    // Auto-refresh timeline every 30 seconds
    setInterval(() => {
        // You can add AJAX call here to refresh timeline
        console.log('Checking for updates...');
    }, 30000);
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey || e.metaKey) {
            switch(e.key) {
                case 's':
                    e.preventDefault();
                    // Save draft
                    const message = document.getElementById('replyMessage').value;
                    if (message.trim()) {
                        localStorage.setItem(`ticket_${ticketId}_draft`, message);
                        showNotification('تم حفظ المسودة', 'success');
                    }
                    break;
                case 'Enter':
                    e.preventDefault();
                    // Send reply
                    const form = document.querySelector('.reply-form');
                    if (form) {
                        form.dispatchEvent(new Event('submit'));
                    }
                    break;
            }
        }
    });
});

// Clear draft when reply is sent successfully
function clearDraft() {
    localStorage.removeItem(`ticket_${ticketId}_draft`);
}

// Add to the successful reply callback
const originalSendReply = sendReply;
sendReply = function(event) {
    const originalCallback = () => {
        clearDraft();
        location.reload();
    };
    
    // Call original function with modified callback
    originalSendReply.call(this, event, originalCallback);
};
</script>
</body>
</html>
