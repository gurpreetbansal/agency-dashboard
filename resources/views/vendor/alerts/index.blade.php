@extends('layouts.vendor_internal_pages')
@section('content')

<div class="white-box pa-0 mb-40">
    <div class="white-box-head">
        <div class="left">
            <div class="heading">
                <img src="{{URL::asset('public/vendor/internal-pages/images/alerts-bell-icon.png')}}">
                <h2>Alerts <span uk-tooltip="title: The section lists the keyword alerts set for different campaigns. View the keywords whose ranking has gone up and down.; pos: top-left" class="fa fa-info-circle"
                    aria-expanded="false"></span></h2>
                </div>
            </div>
        </div>
        <div class="white-box-body">
            <div class="project-table-cover">
                <div class="project-table-head">
                    <div class="project-entries">
                        <label>Show
                            <select id="alerts_limit">
                                <option>20</option>
                                <option selected>50</option>
                                <option>100</option>
                            </select>
                        entries</label>
                    </div>

                    <div class="project-search">
                        <form>
                            <input type="text" placeholder="Search..." id="alerts_search" onkeydown="return (event.keyCode!=13);">
                            <div class="refresh-search-icon" id="refresh-alerts-search">
                                <span uk-icon="refresh"></span>
                            </div>
                            <a href="javascript:;" class="alerts-search-clear" id="alerts-search-clear"><span class="clear-input AlertsClear" uk-icon="icon: close;"></span></a>
                            <button type="submit" onclick="return false;"><span uk-icon="icon: search"></span></button>
                        </form>
                    </div>

                </div>

                <div class="alert-box-tab-head">
                    <ul class="uk-subnav uk-subnav-pill" uk-switcher="connect: .alertBoxContent" id="AlertsTab">
                        <li class="selectedAlerts"><a href="#All">All</a></li>
                        <li class="selectedAlerts"><a href="#Positive">Positive</a></li>
                        <li class="selectedAlerts"><a href="#Negative">Negative</a></li>
                    </ul>
                </div>
                
                <div class="uk-switcher alertBoxContent">
                    <div id="all_section"></div>
                    <div id="positive_section"></div>
                    <div id="negative_section"></div>

                    <input type="hidden"id="alerts_all_limit" value="50" />
                </div>
            </div>
        </div>
    </div>


    @endsection