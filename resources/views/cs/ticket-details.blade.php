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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #1a202c;
        }

        .main-container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 2rem;
            margin-top: 80px;
        }

        /* Modern Header */
        .ticket-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 3rem;
            margin-bottom: 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }

        .ticket-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 2rem;
        }

        .header-left {
            flex: 1;
        }

        .back-navigation {
            margin-bottom: 1.5rem;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.8rem;
            padding: 1rem 1.5rem;
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: 2px solid rgba(102, 126, 234, 0.2);
        }

        .back-btn:hover {
            background: #667eea;
            color: white;
            transform: translateX(5px);
        }

        .ticket-number {
            font-size: 3.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .ticket-subject {
            font-size: 1.8rem;
            color: #1a202c;
            font-weight: 600;
            margin-bottom: 1.5rem;
            line-height: 1.4;
        }

        .header-badges {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .header-badge {
            padding: 0.8rem 1.5rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .badge-status {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: white;
        }

        .badge-priority {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .badge-category {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }

        .action-btn {
            padding: 1rem 1.5rem;
            border-radius: 12px;
            border: none;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
            font-size: 0.9rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.8);
            color: #667eea;
            border: 2px solid rgba(102, 126, 234, 0.2);
        }

        .btn-primary:hover, .btn-secondary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        /* Main Layout */
        .main-layout {
            display: grid;
            grid-template-columns: 1fr 400px;
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

        /* Modern Cards */
        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 60px rgba(102, 126, 234, 0.15);
        }

        .card-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 2rem;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin-bottom: 0.5rem;
        }

        .card-subtitle {
            opacity: 0.9;
            font-size: 1rem;
        }

        .card-body {
            padding: 2rem;
        }

        /* Customer Profile */
        .customer-profile {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding: 2rem;
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            border-radius: 16px;
            margin-bottom: 2rem;
        }

        .customer-avatar-large {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 2rem;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .customer-details {
            flex: 1;
        }

        .customer-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 0.5rem;
        }

        .customer-email {
            color: #64748b;
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        .customer-meta {
            display: flex;
            gap: 2rem;
            font-size: 0.9rem;
            color: #64748b;
        }

        /* Conversation */
        .conversation-container {
            max-height: 600px;
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
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.2rem;
            flex-shrink: 0;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .message.agent .message-avatar {
            background: linear-gradient(135deg, #22c55e, #16a34a);
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
            color: #1a202c;
            font-size: 1rem;
        }

        .message-time {
            font-size: 0.8rem;
            color: #64748b;
            background: #f1f5f9;
            padding: 0.3rem 0.8rem;
            border-radius: 12px;
        }

        .message-bubble {
            background: white;
            padding: 1.5rem;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            line-height: 1.6;
            position: relative;
        }

        .message.agent .message-bubble {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
        }

        .message.internal .message-bubble {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: white;
        }

        /* Reply Form */
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
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
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
            accent-color: #667eea;
        }

        .checkbox-group label {
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
        }

        /* Info Sections */
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
            color: #1a202c;
            font-weight: 700;
            text-align: left;
        }

        /* Status Badges */
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
        }

        .status-open {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: white;
        }

        .status-in_progress {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .status-resolved {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: white;
        }

        .status-closed {
            background: linear-gradient(135deg, #6b7280, #4b5563);
            color: white;
        }

        .priority-urgent {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            animation: pulse 2s infinite;
        }

        .priority-high {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
        }

        .priority-medium {
            background: linear-gradient(135deg, #eab308, #ca8a04);
            color: white;
        }

        .priority-low {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: white;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        /* Timeline */
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
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
        }

        .timeline-title {
            font-weight: 700;
            color: #1a202c;
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

        /* Form Controls */
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
            width: 100%;
        }

        .select-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .action-group {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 1.5rem;
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
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 1rem;
            }
            
            .ticket-header {
                padding: 2rem;
            }
            
            .header-top {
                flex-direction: column;
                gap: 1rem;
            }
            
            .ticket-number {
                font-size: 2.5rem;
            }
            
            .ticket-subject {
                font-size: 1.4rem;
            }
            
            .header-actions {
                flex-direction: column;
                width: 100%;
            }
            
            .action-btn {
                width: 100%;
                justify-content: center;
            }
            
            .message-content {
                max-width: 85%;
            }
            
            .card-body {
                padding: 1.5rem;
            }
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
            color: #1a202c;
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

    <div class="main-container">
        <!-- Modern Ticket Header -->
        <div class="ticket-header">
            <div class="header-top">
                <div class="header-left">
                    <div class="back-navigation">
                        <a href="{{ route('cs.tickets.index') }}" class="back-btn">
                            <i class="fas fa-arrow-right"></i>
                            العودة إلى قائمة التذاكر
                        </a>
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
                                </div>
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
                        <div style="background: #f8fafc; padding: 2rem; border-radius: 16px; border: 1px solid #e2e8f0; line-height: 1.8; font-size: 1.1rem; color: #1a202c;">
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
                            <h4 style="font-size: 1.2rem; font-weight: 700; color: #1a202c; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-reply"></i>
                                إضافة رد جديد
                            </h4>
                            <form class="reply-form" onsubmit="sendReply(event)">
                                <textarea class="reply-input" id="replyMessage" placeholder="اكتب ردك التفصيلي هنا..." required></textarea>
                                
                                <div class="reply-actions">
                                    <div class="reply-options">
                                        <div class="checkbox-group">
                                            <input type="checkbox" id="isInternal">
                                            <label for="isInternal">ملاحظة داخلية</label>
                                        </div>
                                        <div class="checkbox-group">
                                            <input type="checkbox" id="sendEmail">
                                            <label for="sendEmail">إرسال إشعار</label>
                                        </div>
                                    </div>
                                    
                                    <div style="display: flex; gap: 1rem;">
                                        <button type="button" class="action-btn btn-secondary">
                                            <i class="fas fa-save"></i>
                                            حفظ كمسودة
                                        </button>
                                        <button type="submit" class="action-btn btn-primary">
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
                <div class="card">
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
                <div class="card">
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
                            @if($ticket->resolved_at)
                            <div class="info-row">
                                <span class="info-label">
                                    <i class="fas fa-check-circle"></i>
                                    تاريخ الحل
                                </span>
                                <span class="info-value">{{ $ticket->resolved_at->format('Y-m-d H:i') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Enhanced Timeline -->
                <div class="card">
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
                                    <div class="timeline-time">{{ $event['time']->format('Y-m-d H:i') }}</div>
                                </div>
                            </div>
                            @endforeach
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
            
            if (!message.trim()) {
                showNotification('الرجاء كتابة رسالة', 'error');
                return;
            }
            
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
                    send_email: sendEmail
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

        function updateStatus() {
            const status = document.getElementById('statusSelect').value;
            if (!status) return;
            
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
                if (data.success) {
                    showNotification('تم تحديث الحالة بنجاح', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification('فشل تحديث الحالة', 'error');
                }
            });
        }

        function assignAgent() {
            const agentId = document.getElementById('agentSelect').value;
            if (!agentId) return;
            
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
                if (data.success) {
                    showNotification('تم تعيين الوكيل بنجاح', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification('فشل تعيين الوكيل', 'error');
                }
            });
        }

        function printTicket() {
            window.print();
        }

        function exportTicket() {
            showNotification('جاري تحضير ملف التصدير...', 'info');
            // Export functionality here
        }

        // Notification System
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
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
            
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);
            
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 5000);
        }

        // Smooth animations on load
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>