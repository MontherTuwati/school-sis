
@extends('layouts.master')
@section('content')
{{-- message --}}
{!! Toastr::message() !!}

<style>
    /* Modern Dashboard Styling */
    .page-wrapper {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }
    
    .content.container-fluid {
        padding: 2rem;
    }
    
    .page-header {
        background: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .page-title {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }
    
    .breadcrumb {
        background: transparent;
        padding: 0;
        margin: 0;
    }
    
    .breadcrumb-item a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
    }
    
    .breadcrumb-item.active {
        color: #667eea;
    }
    
    /* Stats Cards */
    .card.bg-comman {
        background: white;
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .card.bg-comman:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
    
    .card.bg-comman.card-students {
        border-left: 4px solid #667eea;
    }
    
    .card.bg-comman.card-graduates {
        border-left: 4px solid #28a745;
    }
    
    .card.bg-comman.card-departments {
        border-left: 4px solid #ffc107;
    }
    
    .db-widgets {
        padding: 1.5rem;
    }
    
    .db-info h6 {
        color: #6c757d;
        font-weight: 500;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .db-info h3 {
        font-size: 2.5rem;
        font-weight: bold;
        margin: 0;
        color: #343a40;
    }
    
    .db-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }
    
    .db-icon.students {
        background: #667eea;
    }
    
    .db-icon.graduates {
        background: #28a745;
    }
    
    .db-icon.departments {
        background: #ffc107;
    }
    
    /* Chart Cards */
    .card.card-chart {
        background: white;
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }
    
    .card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 1px solid #dee2e6;
        padding: 1.5rem;
    }
    
    .card-title {
        font-size: 1.25rem;
        font-weight: bold;
        color: #343a40;
        margin: 0;
    }
    
    .chart-list-out {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .chart-list-out li {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        color: #6c757d;
    }
    
    .circle-blue {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #667eea;
    }
    
    .circle-green {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #28a745;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    /* Calendar Widget */
    .card.comman-shadow {
        background: white;
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        height: fit-content;
    }
    
    .calendar-container {
        padding: 1rem;
        border-bottom: 1px solid #dee2e6;
    }
    
    .up-come-header {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-bottom: 1rem;
        padding: 1rem;
    }
    
    .up-come-header h2 {
        font-size: 1.25rem;
        font-weight: bold;
        color: #343a40;
        margin: 0;
    }
    
    .up-come-header span a {
        color: #667eea;
        text-decoration: none;
        font-size: 1.1rem;
    }
    
    .upcome-event-date {
        display: flex;
        justify-content: between;
        align-items: center;
        padding: 0.5rem 1rem;
        background: #f8f9fa;
        margin: 0 1rem;
        border-radius: 8px;
    }
    
    .upcome-event-date h3 {
        font-size: 1rem;
        font-weight: bold;
        color: #343a40;
        margin: 0;
    }
    
    .calendar-details {
        padding: 1rem;
        border-bottom: 1px solid #f1f3f4;
    }
    
    .calendar-details p {
        font-size: 0.9rem;
        color: #667eea;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }
    
    .calendar-box {
        padding: 1rem;
        border-radius: 10px;
        border-left: 4px solid #667eea;
    }
    
    .calendar-box.normal-bg {
        background: #f8f9fa;
    }
    
    .calendar-box.break-bg {
        background: #fff3cd;
        border-left-color: #ffc107;
    }
    
    .calandar-event-name h4 {
        font-size: 1rem;
        font-weight: bold;
        color: #343a40;
        margin: 0 0 0.25rem 0;
    }
    
    .calandar-event-name h5 {
        font-size: 0.9rem;
        color: #6c757d;
        margin: 0 0 0.5rem 0;
    }
    
    .calendar-box span {
        font-size: 0.8rem;
        color: #667eea;
        font-weight: 500;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .content.container-fluid {
            padding: 1rem;
        }
        
        .page-header {
            padding: 1.5rem;
        }
        
        .page-title {
            font-size: 1.5rem;
        }
    }
</style>

<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Welcome {{ Session::get('name') }}!</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">{{ Session::get('name') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100 card-students">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Students</h6>
                                <h3>50+</h3>
                            </div>
                            <div class="db-icon students">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100 card-graduates">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Graduates</h6>
                                <h3>50+</h3>
                            </div>
                            <div class="db-icon graduates">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100 card-departments">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Department</h6>
                                <h3>23</h3>
                            </div>
                            <div class="db-icon departments">
                                <i class="fas fa-building"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-12 col-xl-8">
                <div class="card card-chart">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h5 class="card-title">Overview</h5>
                            </div>
                            <div class="col-6">
                                <ul class="chart-list-out">
                                    <li><span class="circle-blue"></span>Teacher</li>
                                    <li><span class="circle-green"></span>Student</li>
                                    <li class="star-menus"><a href="javascript:;"><i
                                                class="fas fa-ellipsis-v"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="apexcharts-area"></div>
                    </div>
                </div>
                <div class="card card-chart">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h5 class="card-title">Number of Students</h5>
                            </div>
                            <div class="col-6">
                                <ul class="chart-list-out">
                                    <li><span class="circle-blue"></span>Girls</li>
                                    <li><span class="circle-green"></span>Boys</li>
                                    <li class="star-menus"><a href="javascript:;"><i
                                                class="fas fa-ellipsis-v"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-12 col-xl-4 d-flex">
                    <div class="card flex-fill comman-shadow">
                        <div class="card-body">
                            <div id="calendar-doctor" class="calendar-container"></div>
                            <div class="calendar-info calendar-info1">
                                <div class="up-come-header">
                                    <h2>Upcoming Events</h2>
                                    <span><a href="javascript:;"><i class="feather-plus"></i></a></span>
                                </div>
                                <div class="upcome-event-date">
                                    <h3>10 Jan</h3>
                                    <span><i class="fas fa-ellipsis-h"></i></span>
                                </div>
                                <div class="calendar-details">
                                    <p>08:00 am</p>
                                    <div class="calendar-box normal-bg">
                                        <div class="calandar-event-name">
                                            <h4>Botony</h4>
                                            <h5>Lorem ipsum sit amet</h5>
                                        </div>
                                        <span>08:00 - 09:00 am</span>
                                    </div>
                                </div>
                                <div class="calendar-details">
                                    <p>09:00 am</p>
                                    <div class="calendar-box normal-bg">
                                        <div class="calandar-event-name">
                                            <h4>Botony</h4>
                                            <h5>Lorem ipsum sit amet</h5>
                                        </div>
                                        <span>09:00 - 10:00 am</span>
                                    </div>
                                </div>
                                <div class="calendar-details">
                                    <p>10:00 am</p>
                                    <div class="calendar-box normal-bg">
                                        <div class="calandar-event-name">
                                            <h4>Botony</h4>
                                            <h5>Lorem ipsum sit amet</h5>
                                        </div>
                                        <span>10:00 - 11:00 am</span>
                                    </div>
                                </div>
                                <div class="upcome-event-date">
                                    <h3>10 Jan</h3>
                                    <span><i class="fas fa-ellipsis-h"></i></span>
                                </div>
                                <div class="calendar-details">
                                    <p>08:00 am</p>
                                    <div class="calendar-box normal-bg">
                                        <div class="calandar-event-name">
                                            <h4>English</h4>
                                            <h5>Lorem ipsum sit amet</h5>
                                        </div>
                                        <span>08:00 - 09:00 am</span>
                                    </div>
                                </div>
                                <div class="calendar-details">
                                    <p>09:00 am</p>
                                    <div class="calendar-box normal-bg">
                                        <div class="calandar-event-name">
                                            <h4>Mathematics </h4>
                                            <h5>Lorem ipsum sit amet</h5>
                                        </div>
                                        <span>09:00 - 10:00 am</span>
                                    </div>
                                </div>
                                <div class="calendar-details">
                                    <p>10:00 am</p>
                                    <div class="calendar-box normal-bg">
                                        <div class="calandar-event-name">
                                            <h4>History</h4>
                                            <h5>Lorem ipsum sit amet</h5>
                                        </div>
                                        <span>10:00 - 11:00 am</span>
                                    </div>
                                </div>
                                <div class="calendar-details">
                                    <p>11:00 am</p>
                                    <div class="calendar-box break-bg">
                                        <div class="calandar-event-name">
                                            <h4>Break</h4>
                                            <h5>Lorem ipsum sit amet</h5>
                                        </div>
                                        <span>11:00 - 12:00 am</span>
                                    </div>
                                </div>
                                <div class="calendar-details">
                                    <p>11:30 am</p>
                                    <div class="calendar-box normal-bg">
                                        <div class="calandar-event-name">
                                            <h4>History</h4>
                                            <h5>Lorem ipsum sit amet</h5>
                                        </div>
                                        <span>11:30 - 12:00 am</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
</div>
@endsection
