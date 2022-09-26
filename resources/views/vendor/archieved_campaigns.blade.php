@extends('layouts.vendor_internal_pages')
@section('content')
<!-- <div class="project-stats">
    <div uk-grid class="mb-40">
        <div class="uk-width-1-3@l uk-width-1-3@s">
            <div class="white-box small-chart-box style2">

            </div>
        </div>
        <div class="uk-width-1-3@l uk-width-1-3@s">
            <div class="white-box small-chart-box style2">

            </div>
        </div>
        <div class="uk-width-1-3@l uk-width-1-3@s">
            <div class="white-box small-chart-box style2">

            </div>
        </div>
    </div>
</div> -->

<div class="white-box pa-0 mb-40">
    <div class="white-box-head">
        <div class="left">
            <div class="loader h-33 half-px"></div>
            <div class="heading">
                <img src="{{URL::asset('public/vendor/internal-pages/images/archive-icon.png')}}">
                <h2>Archived Campaigns
                    <span uk-tooltip="title: Archived Campaigns you are no longer working on. All the inactive projects are listed here for as long as you want; pos: top-left" class="fa fa-info-circle"></span></h2>
                </div>
            </div>
            <div class="right">
                <div class="loader h-33 half-px"></div>
                <div class="btn-group">

                 

                    <!-- already archived projects -->
                    <a href="{{url('/archived-campaigns')}}" class="btn icon-btn color-orange restore_archived_campaigns" uk-tooltip="title: Restore Campaigns; pos: top-center">
                        <img src="{{URL::asset('public/vendor/internal-pages/images/restore-icon.png')}}">
                    </a>
                    @if(Auth::user()->role_id == 2) 
                    <!-- archiving projects -->
                    <a href="javascript:;" class="btn icon-btn color-red archived_delete_campaign" uk-tooltip="title: Delete Campaigns; pos: top-center">
                        <img src="{{URL::asset('public/vendor/internal-pages/images/delete-icon.png')}}">
                    </a>
                    @endif

                </div>
            </div>
        </div>
        <div class="white-box-body">
            <div class="loader h-300-table"></div>
            <div class="project-table-cover">
                <div class="project-table-head">
                    <div class="project-entries">
                        <label>Show
                            <select class="ArchivedCampaignsToList">
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        entries</label>
                    </div>

                    <div class="project-search style2">
                        <div id="archived-filter-search-form" class="filter-search-form">
                            <span class="archived-selected-filter-text">project:</span>
                            <input type="text" placeholder="Search..." class="archived_campaign_search" id="archived_campaign_search" autocomplete="off">
                            <div class="refresh-search-icon" id="refresh-archived-search">
                                <span uk-icon="refresh"></span>
                            </div>
                            <a href="javascript:;" class="archived-search-clear"><span class="clear-input ArchivedCampaignsClear" uk-icon="icon: close;"></span></a> 
                            <button type="submit"><span uk-icon="icon: search"></span></button>
                            <div class="archived-search-filter">
                                <p>Suggested Filters</p>
                                <ul>
                                    @if(Auth::user()->role_id == 2)
                                    <li class="search-filter-list"><span>project:</span> search by project name</li>
                                    <li class="search-filter-list"><span>client:</span> search by client name</li>
                                    <li class="search-filter-list"><span>manager:</span> search by manager name</li>
                                    <li class="search-filter-list"><span>tags:</span> search by tag</li>
                                    @endif
                                    @if(Auth::user()->role_id == 3)
                                    <li class="search-filter-list"><span>project:</span> search by project name</li>
                                    <li class="search-filter-list"><span>client:</span> search by client name</li>
                                    <li class="search-filter-list"><span>tags:</span> search by tag</li>
                                    @endif
                                    @if(Auth::user()->role_id == 4)
                                    <li class="search-filter-list"><span>project:</span> search by project name</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="project-table-body table-overflow">
                    <div class="ArchivedloaderSection"></div>
                    <table id="archived-campaign-list">
                        <thead>
                            <tr>
                                <th class="archived_sorting" data-sorting_type="asc" data-column_name="domain_name">
                                    <span uk-icon="arrow-up" ></span>
                                    <span uk-icon="arrow-down"></span>
                                    Project Name
                                </th>
                                <th>    
                                    Integration 
                                </th>
                                <th class="archived_sorting" data-sorting_type="asc" data-column_name="searcher">
                                    <span uk-icon="arrow-up" ></span>
                                    <span uk-icon="arrow-down"></span>
                                    Searcher
                                </th>
                                <th class="archived_sorting" data-sorting_type="asc" data-column_name="country">
                                    <span uk-icon="arrow-up" ></span>
                                    <span uk-icon="arrow-down" ></span>
                                    Country
                                </th>
                                <th class="archived_sorting" data-sorting_type="asc" data-column_name="keywords">
                                    <span uk-icon="arrow-up" ></span>
                                    <span uk-icon="arrow-down" ></span>
                                    Keywords
                                </th>
                                <th class="archived_sorting" data-sorting_type="asc" data-column_name="top3">
                                    <span uk-icon="arrow-up" ></span>
                                    <span uk-icon="arrow-down" ></span>
                                    Top 3
                                </th>
                                <th class="archived_sorting" data-sorting_type="asc" data-column_name="top10">
                                    <span uk-icon="arrow-up" ></span>
                                    <span uk-icon="arrow-down" ></span>
                                    Top 10
                                </th>
                                <th class="archived_sorting" data-sorting_type="asc" data-column_name="top20">
                                    <span uk-icon="arrow-up" ></span>
                                    <span uk-icon="arrow-down" ></span>
                                    Top 20
                                </th>
                                <th class="archived_sorting" data-sorting_type="asc" data-column_name="top100">
                                    <span uk-icon="arrow-up" ></span>
                                    <span uk-icon="arrow-down" ></span>
                                    Top 100
                                </th>
                                <th class="archived_sorting" data-sorting_type="asc" data-column_name="backlinks">
                                    <span uk-icon="arrow-up" ></span>
                                    <span uk-icon="arrow-down" ></span>
                                    Backlinks
                                </th>
                                <th>
                                    Actions
                                </th>

                                <th>
                                    <input class="uk-checkbox" type="checkbox" id="checkAllArchived">
                                </th>
                            </tr>
                        </thead>
                        <tbody >
                            @for($i=0;$i<=5;$i++)   
                            <tr>    
                                <td class="ajax-loader">..</td> 
                                <td class="ajax-loader">..</td> 
                                <td class="ajax-loader">..</td> 
                                <td class="ajax-loader">..</td> 
                                <td class="ajax-loader">..</td> 
                                <td class="ajax-loader">..</td> 
                                <td class="ajax-loader">..</td> 
                                <td class="ajax-loader">..</td> 
                                <td class="ajax-loader">..</td> 
                                <td class="ajax-loader">..</td> 
                                <td class="ajax-loader">..</td> 
                                <td class="ajax-loader">..</td> 
                            </tr>   
                            @endfor
                        </tbody>
                    </table> 
                </div>
                <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="domain_name" />
                <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
                <input type="hidden" name="limit" id="limit" value="20" />


                <div class="archived project-table-foot archived-project-table-foot">
                    <div class="project-entries ajax-loader"><p>....</p></div>
                    <div class="archived-pagination ajax-loader">
                        <ul>
                            <li>..</li>
                            <li>..</li>
                            <li>..</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection