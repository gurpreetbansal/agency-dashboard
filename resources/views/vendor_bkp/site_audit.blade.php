@extends('layouts.vendor_internal_pages')
@section('content')
<div class="audit-white-box mb-40">
            <div class="elem-flex">
                <div class="elem-start">
                    <div class="circle_percent hiddenOnLoad" data-percent="33">
                        <div class="circle_inner">
                            <div class="round_per">
                            </div>
                        </div>
                    </div>
                    <div class="loader"></div>
                    <div class="score-for">
                        <h2><small>Website score for</small>sacramento4kids.com</h2>
                        <p><a href="#">How is it calculated?</a></p>
                        <ul>
                            <li>IP: 35.209.111.29</li>
                            <li>|</li>
                            <li>SSL: enabled</li>
                        </ul>
                        <a href="#" class="btn btn-sm blue-btn">View issues</a>
                    </div>
                </div>

                <div class="elem-end">
                    <div class="loader h-48 loader-text"></div>
                    <div class="btn-group">
                        <a href="#" class="btn icon-btn color-orange"
                            uk-tooltip="title: Refresh Report; pos: top-center">
                            <img src="{{URL::asset('public/vendor/internal-pages/images/refresh-yellow-icon.png')}}">
                        </a>
                        <a href="#" class="btn icon-btn color-red"
                            uk-tooltip="title: Generate PDF File; pos: top-center">
                            <img src="{{URL::asset('public/vendor/internal-pages/images/pdf-icon.png')}}">
                        </a>
                        <a href="#" class="btn icon-btn color-blue" uk-tooltip="title:Project Setting; pos: top-center">
                            <img src="{{URL::asset('public/vendor/internal-pages/images/setting-icon.png')}}">
                        </a>
                        <a href="#" id="ShareKey" class="btn icon-btn color-purple"
                            uk-tooltip="title: Generate Shared Key; pos: top-center">
                            <img src="{{URL::asset('public/vendor/internal-pages/images/share-key-icon.png')}}">
                        </a>
                    </div>

                    <article>
                        <div class="loader h-54 "></div>
                        <ul>
                            <li>
                                <div>Crawled pages</div>
                                <div>600</div>
                            </li>
                            <li>
                                <div>Current crawling</div>
                                <div>00:23, Jan 22</div>
                            </li>
                            <li>
                                <div>Previous crawling</div>
                                <div>00:19, Jan 21</div>
                            </li>
                        </ul>
                        <div class="loader h-54 "></div>
                        <ul>
                            <li>
                                <div><img src="{{URL::asset('public/vendor/internal-pages/images/google-indexed-logo.png')}}"> Google indexed pages</div>
                                <div>0</div>
                            </li>
                            <li>
                                <div><img src="{{URL::asset('public/vendor/internal-pages/images/google-safe-browsing-logo.png')}}"> Google safe browsing</div>
                                <div>Site is safe</div>
                            </li>
                        </ul>
                    </article>
                </div>
            </div>
        </div>

        <div class="audit-white-box mb-40 pa-0">
            <div class="audit-box-head">
            <div class="loader h-48 "></div>
                <h2>Site-level issues <small>(found 0)</small> <span
                        uk-tooltip="title: Site-level issues Here...; pos: top-left" class="fa fa-info-circle"></span>
                </h2>
            </div>
            <div class="audit-box-body">
            <div class="loader h-300-table"></div>
                <table>
                    <tbody>
                        <tr>
                            <td>
                                <img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}">
                                XML sitemap
                            </td>
                            <td>Site has XML sitemap file</td>
                        </tr>
                        <tr>
                            <td>
                                <img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}">
                                Robots.txt
                            </td>
                            <td>Site has robots.txt file</td>
                        </tr>
                        <tr>
                            <td>
                                <img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}">
                                Favicon
                            </td>
                            <td>Site has a favicon</td>
                        </tr>
                        <tr>
                            <td>
                                <img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}">
                                404 page
                            </td>
                            <td>Error page responded 404 status code</td>
                        </tr>
                        <tr>
                            <td>
                                <img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}">
                                HTTP and HTTPS
                            </td>
                            <td>Working protocol redirect: HTTP to HTTPS</td>
                        </tr>
                    </tbody>
                    <tbody class="table-collapseed">
                        <tr>
                            <td>
                                <img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}">
                                Favicon
                            </td>
                            <td>Site has a favicon</td>
                        </tr>
                        <tr>
                            <td>
                                <img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}">
                                404 page
                            </td>
                            <td>Error page responded 404 status code</td>
                        </tr>
                        <tr>
                            <td>
                                <img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}">
                                HTTP and HTTPS
                            </td>
                            <td>Working protocol redirect: HTTP to HTTPS</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="audit-box-foot">
            <div class="loader h-48 "></div>
                <a href="javascript:void(0);" class="show-more-issues"><span uk-icon="icon:triangle-down"></span> <span class="t">Show More</span></a>
            </div>
        </div>

        <div class="audit-white-box pa-0 hiddenOnLoad" id="PageLevelIssues">
            <div class="audit-box-head">
                <h2>Page-level issues <span uk-tooltip="title: Page-level issues Here...; pos: top-left"
                        class="fa fa-info-circle"></span>
                </h2>
            </div>
            <div class="audit-box-body">
                <div class="audit-stats">
                    <div class="audit-stats-box red">
                        <figure>
                            <img src="{{URL::asset('public/vendor/internal-pages/images/criticals-icon.png')}}">
                        </figure>
                        <h3>4217 <small>Criticals</small></h3>
                        <div class="number red">
                            <span uk-icon="icon: arrow-down"></span>
                            1
                        </div>
                    </div>
                    <div class="audit-stats-box yellow">
                        <figure>
                            <img src="{{URL::asset('public/vendor/internal-pages/images/warnings-icon.png')}}">
                        </figure>
                        <h3>7437 <small>Warnings</small></h3>
                        <div class="number red">
                            <span uk-icon="icon: arrow-down"></span>
                            33
                        </div>
                    </div>
                    <div class="audit-stats-box blue">
                        <figure>
                            <img src="{{URL::asset('public/vendor/internal-pages/images/notices-icon.png')}}">
                        </figure>
                        <h3>3267 <small>Notices</small></h3>
                        <div class="number green">
                            <span uk-icon="icon: arrow-up"></span>
                            13
                        </div>
                    </div>
                </div>

                <div class="audit-issues">
                    <ul class="uk-subnav uk-subnav-pill" uk-switcher="connect: .auditIssuesContainer">
                        <li><a href="#">All Issues</a></li>
                        <li><a href="#">Critical Errors</a></li>
                        <li><a href="#">Warnings</a></li>
                        <li><a href="#">Notices</a></li>
                        <li><a href="#">Zero Issues</a></li>
                    </ul>
                    <div class="content">
                        <p>Here is a list of all technical issues Agency Dashboard has found on the website. Start fix
                            them
                            step by step from the most critical errors to less important. When you finished fixing
                            issues,
                            recrawl the website to make sure Website Score is up.</p>
                    </div>
                    <div class="tab-content ">
                        <div class="uk-switcher auditIssuesContainer">
                            <!-- Tab 1 All Issues -->
                            <div>
                                <table>
                                    <tr>
                                        <td class="issue-type critical">Canonical</td>
                                        <td>15 pages</td>
                                        <td><i class="icon ion-arrow-down-a"></i> 2</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-border blue-btn-border"><i
                                                    class="fa fa-question-circle" aria-hidden="true"></i> How to fix</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="issue-type critical">Title duplicates</td>
                                        <td>25 pages</td>
                                        <td><i class="icon ion-arrow-up-a"></i> 2</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-border blue-btn-border"><i
                                                    class="fa fa-question-circle" aria-hidden="true"></i> How to fix</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="issue-type critical">Meta Tag duplicates</td>
                                        <td>15 pages</td>
                                        <td><i class="icon ion-arrow-down-a"></i> 6</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-border blue-btn-border"><i
                                                    class="fa fa-question-circle" aria-hidden="true"></i> How to fix</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="issue-type warnings">Description duplicate</td>
                                        <td>65 pages</td>
                                        <td><i class="icon ion-arrow-down-a"></i> 7</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-border blue-btn-border"><i
                                                    class="fa fa-question-circle" aria-hidden="true"></i> How to fix</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="issue-type warnings">Missing Image alt text</td>
                                        <td>5 pages</td>
                                        <td><i class="icon ion-arrow-down-a"></i> 14</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-border blue-btn-border"><i
                                                    class="fa fa-question-circle" aria-hidden="true"></i> How to fix</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="issue-type notices">Missing Image title text</td>
                                        <td>25 pages</td>
                                        <td><i class="icon ion-arrow-down-a"></i> 84</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-border blue-btn-border"><i
                                                    class="fa fa-question-circle" aria-hidden="true"></i> How to fix</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="issue-type notices">SEO Friendly URL</td>
                                        <td>2 pages</td>
                                        <td><i class="icon ion-arrow-up-a"></i> 23</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-border blue-btn-border"><i
                                                    class="fa fa-question-circle" aria-hidden="true"></i> How to fix</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="issue-type notices">Title too short</td>
                                        <td>7 pages</td>
                                        <td><i class="icon ion-arrow-up-a"></i> 8</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-border blue-btn-border"><i
                                                    class="fa fa-question-circle" aria-hidden="true"></i> How to fix</a>
                                        </td>
                                    </tr>
                                    <tbody class="table-audit-collapseed">
                                        <tr>
                                            <td class="issue-type warnings">Description duplicate</td>
                                            <td>65 pages</td>
                                            <td><i class="icon ion-arrow-down-a"></i> 7</td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-border blue-btn-border"><i
                                                        class="fa fa-question-circle" aria-hidden="true"></i> How to
                                                    fix</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="issue-type warnings">Missing Image alt text</td>
                                            <td>5 pages</td>
                                            <td><i class="icon ion-arrow-down-a"></i> 14</td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-border blue-btn-border"><i
                                                        class="fa fa-question-circle" aria-hidden="true"></i> How to
                                                    fix</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="issue-type notices">Missing Image title text</td>
                                            <td>25 pages</td>
                                            <td><i class="icon ion-arrow-down-a"></i> 84</td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-border blue-btn-border"><i
                                                        class="fa fa-question-circle" aria-hidden="true"></i> How to
                                                    fix</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="issue-type notices">SEO Friendly URL</td>
                                            <td>2 pages</td>
                                            <td><i class="icon ion-arrow-up-a"></i> 23</td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-border blue-btn-border"><i
                                                        class="fa fa-question-circle" aria-hidden="true"></i> How to
                                                    fix</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="issue-type notices">Title too short</td>
                                            <td>7 pages</td>
                                            <td><i class="icon ion-arrow-up-a"></i> 8</td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-border blue-btn-border"><i
                                                        class="fa fa-question-circle" aria-hidden="true"></i> How to
                                                    fix</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="audit-box-foot">
                                    <a href="javascript:void(0);" class="show-more-audit-issues"><span
                                            uk-icon="icon:triangle-down"></span> <span class="t">Show More</span></a>
                                </div>
                            </div>
                            <!-- Tab 1 All Issues End -->

                            <!-- Tab 2 Critical -->
                            <div>
                                <table>
                                    <tr>
                                        <td class="issue-type critical">Canonical</td>
                                        <td>15 pages</td>
                                        <td><i class="icon ion-arrow-down-a"></i> 2</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-border blue-btn-border"><i
                                                    class="fa fa-question-circle" aria-hidden="true"></i> How to fix</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="issue-type critical">Title duplicates</td>
                                        <td>25 pages</td>
                                        <td><i class="icon ion-arrow-up-a"></i> 2</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-border blue-btn-border"><i
                                                    class="fa fa-question-circle" aria-hidden="true"></i> How to fix</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="issue-type critical">Meta Tag duplicates</td>
                                        <td>15 pages</td>
                                        <td><i class="icon ion-arrow-down-a"></i> 6</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-border blue-btn-border"><i
                                                    class="fa fa-question-circle" aria-hidden="true"></i> How to fix</a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <!-- Tab 2 Critical End -->

                            <!-- Tab 3 Warnings -->
                            <div>
                                <table>
                                    <tr>
                                        <td class="issue-type warnings">Description duplicate</td>
                                        <td>65 pages</td>
                                        <td><i class="icon ion-arrow-down-a"></i> 7</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-border blue-btn-border"><i
                                                    class="fa fa-question-circle" aria-hidden="true"></i> How to fix</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="issue-type warnings">Missing Image alt text</td>
                                        <td>5 pages</td>
                                        <td><i class="icon ion-arrow-down-a"></i> 14</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-border blue-btn-border"><i
                                                    class="fa fa-question-circle" aria-hidden="true"></i> How to fix</a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <!-- Tab 3 Warnings End -->

                            <!-- Tab 4 Notices -->
                            <div>
                                <table>
                                    <tr>
                                        <td class="issue-type notices">Missing Image title text</td>
                                        <td>25 pages</td>
                                        <td><i class="icon ion-arrow-down-a"></i> 84</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-border blue-btn-border"><i
                                                    class="fa fa-question-circle" aria-hidden="true"></i> How to fix</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="issue-type notices">SEO Friendly URL</td>
                                        <td>2 pages</td>
                                        <td><i class="icon ion-arrow-up-a"></i> 23</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-border blue-btn-border"><i
                                                    class="fa fa-question-circle" aria-hidden="true"></i> How to fix</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="issue-type notices">Title too short</td>
                                        <td>7 pages</td>
                                        <td><i class="icon ion-arrow-up-a"></i> 8</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-border blue-btn-border"><i
                                                    class="fa fa-question-circle" aria-hidden="true"></i> How to fix</a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <!-- Tab 4 Notices End -->

                            <!-- Tab 5 Zero Issues -->
                            <div></div>
                            <!-- Tab 5 Zero Issues End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection