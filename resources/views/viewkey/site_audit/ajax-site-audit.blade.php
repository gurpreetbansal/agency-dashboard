<?php

$urlsSource = $summaryTask['domain_info']['checks']['ssl'] == 1 ? 'https://':'http://'; 
$iswww = $summaryTask['page_metrics']['checks']['is_www'] <> 1 ? 'www.' : '' ; 

if($summaryTask['page_metrics']['checks']['is_www'] == 0){
    $iswww = '';
}else if(($summaryTask['crawl_status']['pages_crawled'] - $summaryTask['page_metrics']['checks']['is_www']) == 0){
    $iswww = 'www.';
}else if(($summaryTask['crawl_status']['pages_crawled'] - $summaryTask['page_metrics']['checks']['is_www']) < $summaryTask['crawl_status']['pages_crawled']){
    $iswww = '';
}else{
   $iswww = ''; 
}
   
?>
<div class="audit-white-box mb-40">
    <div class="elem-flex">
        <div class="elem-start">
            <div class="circle-donut" style="width:208px;height:208px;">
                <div class="circle_inbox">
                    @if($summaryTask['crawl_progress'] == 'finished')
                    <span class="percent_text">{{ (int)$summaryTask['page_metrics']['onpage_score'] }}</span> of 100
                    @else
                    <span class="jumping-dots-loader"><span></span> <span></span> <span></span></span> of 100
                    @endif
                </div>
                <input type="hidden" class="siteAudit-chart-data" value="{{ (int)$summaryTask['page_metrics']['onpage_score'] }}">
                <canvas id="siteAudit-chart-data" width="50" height="50"></canvas>
            </div>
            <div class="score-for">
                <h2><small>Website score for</small>{{ $summaryTask['domain_info']['name'] }}</h2>
                <p><a data-pd-popup-open="howisitcalculated">How is it calculated?</a></p>
                <ul>
                    <li><i class="fa fa-map-marker" aria-hidden="true"></i> {{ $summaryTask['domain_info']['ip'] }}</li>
                    <li>|</li>
                    <li><?php if($summaryTask['domain_info']['checks']['ssl'] == 1){ ?><i class='fa fa-lock' aria-hidden='true'></i> <?php echo ' enabled'; }else{?><i class="fa fa-unlock" aria-hidden="true"></i><?php echo ' Not enabled';}?></li>
                </ul>
                <a href="javascript:;" uk-toggle="target: #offcanvas-pagecode" data-url="{{ $urlsSource . $iswww .$summaryTask['domain_info']['name'] }}/" data-title="" class="btn btn-sm blue-btn viewsource"><i class="fa fa-code"></i> View page code</a>
                <a href="javascript:;" class="btn btn-sm btn-border blue-btn-border viewPages" data-filter="" >View issues</a>
            </div>
        </div>

        <div class="elem-end">
            <article>
                <div class="progress-loader"> </div>
                <ul>
                    <li>
                        <div>Crawled pages</div>
                        <div class="crawled-pages">
                            {{ $summaryTask['crawl_status']['pages_crawled'] .' /'. $summaryTask['crawl_status']['max_crawl_pages'] }}
                        </div>
                    </li>
                    <li>
                        <div>Latest Crawl</div>
                        <div>{{ date('M d Y h:i A',strtotime($summaryTask['domain_info']['crawl_start'])) }}</div>
                    </li>
                    @if($previousTask <> null && isset($previousSummary['domain_info']) && ($previousSummary['domain_info'] <> null))
                    <li>
                        <div>Previous Crawl</div>
                        <div>
                            @if($previousTask <> null && isset($previousSummary['domain_info']) && ($previousSummary['domain_info'] <> null))
                            {{ date('M d Y h:i A',strtotime($previousSummary['domain_info']['crawl_end'])) }}
                            @else
                            {{ 'N/A' }}
                            @endif
                        </div>
                    </li>
                    @endif
                </ul>
                <ul>
                    <li>
                        <div><img src="{{URL::asset('public/vendor/internal-pages/images/google-indexed-logo.png')}}">
                        Google indexed pages</div>
                        @if($summaryTask['crawl_progress'] == 'finished')
                        <div>{{ $summaryTask['domain_info']['total_pages'] - $nonidexTask['total_items_count'] }}</div>
                        @else
                        <div class="jumping-dots-loader"> <span></span> <span></span> <span></span> </div>
                        @endif
                    </li>
                    <li>
                        <div>
                            @if($summaryTask['domain_info']['checks']['ssl'] == 1)
                            <img src="{{URL::asset('public/vendor/internal-pages/images/google-safe-icon.png')}}">
                            @else
                            <img src="{{URL::asset('public/vendor/internal-pages/images/google-safe-browsing-logo.png')}}">
                            @endif
                            Google safe browsing
                        </div>
                        <div>
                            {{ $summaryTask['domain_info']['checks']['ssl'] == 1 ? "Site is safe": "Site is not safe" }}
                        </div>
                    </li>
                </ul>
            </article>
        </div>
    </div>
</div>


<div class="audit-white-box mb-40 pa-0">
    <div class="audit-box-head">
        
        <h2>Site-level issues <small>(found {{$issueCount}})</small> <span uk-tooltip="title: It displays a list of fundamental technical issues on your site. ; pos: top-left" class="fa fa-info-circle"></span>
        </h2>
    </div>
    <div class="audit-box-body">
        <table class="site-level-issues">
            <tbody>
                <tr>
                    <td>
                        @if($summaryTask['domain_info']['checks']['sitemap'] == 1)
                        <img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}">
                        @else
                        <img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
                        @endif
                        XML sitemap
                    </td>
                    <td>
                        @if($summaryTask['domain_info']['checks']['sitemap'] == 1)
                        Site has
                        <a target="_blank"  href="https://{{ $summaryTask['domain_info']['main_domain'] }}/sitemap_index.xml"> XML sitemap file </a>
                        @else
                        Site does not have XML sitemap file
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>
                        @if($summaryTask['domain_info']['checks']['robots_txt'] == 1 )
                        <img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}">
                        @else
                        <img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
                        @endif
                        Robots.txt
                    </td>
                    <td>
                        @if($summaryTask['domain_info']['checks']['robots_txt'] == 1 )
                        Site has <a target="_blank"
                        href="https://{{ $summaryTask['domain_info']['main_domain'] }}/robots.txt">robots.txt file</a> 
                        @else
                        Site does not have robots.txt
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="pa-0">
                        <div class="table-collapseed" style="display: none;">
                            <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            @if($summaryTask['page_metrics']['checks']['no_favicon'] > 0)
                                            <img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
                                            @else
                                            <img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}">
                                            @endif
                                            Favicon
                                        </td>
                                        <td>{{ $summaryTask['page_metrics']['checks']['no_favicon'] > 0 ? $summaryTask['page_metrics']['checks']['no_favicon']." page(s) do not have favicon" : "Site has a favicon" }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            @if($summaryTask['page_metrics']['checks']['is_4xx_code'] == 0 )
                                            <img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}">
                                            @else
                                            <img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
                                            @endif
                                            4XX pages
                                        </td>
                                        <td>{{ $summaryTask['page_metrics']['checks']['is_4xx_code'] == 0 ? "No 4XX pages" : $summaryTask['page_metrics']['checks']['is_4xx_code']." page(s) responded with 4xx code"}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            @if($summaryTask['page_metrics']['checks']['is_http'] == 0)
                                            <img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}">
                                            @else
                                            <img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
                                            @endif
                                            HTTP and HTTPS
                                        </td>
                                        <td>
                                            <?php 
                                            if($summaryTask['page_metrics']['checks']['is_http'] == 0){
                                                echo "Working protocol redirect: HTTP to HTTPS";
                                            }else{
                                               $http_count =  $summaryTask['crawl_status']['pages_crawled'] - $summaryTask['page_metrics']['checks']['is_https'];
                                               echo $http_count. " page(s) working on http protocol";
                                           }
                                           ?>
                                       </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}">
                                            WWW and non-WWW
                                        </td>
                                        <td>
                                            <?php 
                                            if($summaryTask['page_metrics']['checks']['is_www'] == 0){
                                                echo "All pages of your domain are on www";
                                            }else{
                                                echo $summaryTask['page_metrics']['checks']['is_www']. " page(s) of your domain are on www";
                                            }
                                            ?>

                                        </td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="audit-box-foot">
        <a href="javascript:void(0);" class="show-more-issues"><span uk-icon="icon:triangle-down"></span> <span
            class="t">Show More</span></a>
    </div>
</div>

<div class="audit-white-box pa-0" id="PageLevelIssues">
    <div class="audit-box-head">
        <h2>Page-level issues 
            <span uk-tooltip="title: It displays a comprehensive list of technical issues spotted on different pages of your site.; pos: top-left" class="fa fa-info-circle"></span> 
        </h2>
    </div>
    <div class="audit-box-body">
        <div class="audit-stats">
            <a href="javascript:;" class="viewPages" data-filter="critical" >
                <div class="audit-stats-box red">
                    <figure>
                        <img src="{{URL::asset('public/vendor/internal-pages/images/criticals-icon.png')}}">
                    </figure>
                    <h3>
                        {{ array_sum($errorsListing['critical']) }}
                        <small>Criticals</small>
                    </h3>
                    <i class="fa fa-long-arrow-right"></i>
                    @if($previousTask <> null)
                    <?php 
                    $criticalSum = (array_sum($previousTask['critical'])) - (array_sum($errorsListing['critical']));                    
                    $criticalColor = '';
                    $criticalArrow = '';
                    if($criticalSum > 0){
                        $criticalColor = 'number red';
                        $criticalArrow = '<span uk-icon="icon: arrow-up"></span>';    

                    }elseif($criticalSum < 0){
                        $criticalColor = 'number green'; 
                        $criticalArrow = '<span uk-icon="icon: arrow-down"></span>';     
                        $criticalSum =  str_replace('-', '', $criticalSum);
                    }
                    ?>

                    @if($criticalColor <> "")
                    <div class="{{ $criticalColor }}">
                        {!! $criticalArrow !!}
                        {!! $criticalSum <> 0 ? $criticalSum :''; !!}
                    </div>
                    @endif

                    @endif
                </div>
            </a>
            <a href="javascript:;" class="viewPages" data-filter="warning" >
                <div class="audit-stats-box yellow">
                    <figure>
                        <img src="{{URL::asset('public/vendor/internal-pages/images/warnings-icon.png')}}">
                    </figure>
                    <h3>
                        {{ array_sum($errorsListing['warning']) }}
                        <small>Warnings</small>
                    </h3>
                    <i class="fa fa-long-arrow-right"></i>
                    @if($previousTask <> null)
                    <?php 
                    $warningSum = (array_sum($previousTask['warning'])) - (array_sum($errorsListing['warning']));
                    $warningColor = '';
                    $warningArrow = '';
                    if($warningSum > 0){
                        $warningColor = 'number red';
                        $warningArrow = '<span uk-icon="icon: arrow-up"></span>';                        
                    }elseif($warningSum < 0){
                        $warningColor = 'number green'; 
                        $warningArrow = '<span uk-icon="icon: arrow-down"></span>';
                        $warningSum =  str_replace('-', '', $warningSum);                    
                    }
                    ?>
                    @if($warningColor <> "")
                    <div class="{{ $warningColor }}">
                        {!! $warningArrow !!}
                        {!! $warningSum <> 0 ? $warningSum :''; !!}
                    </div>
                    @endif
                    @endif
                </div>
            </a>
            <a href="javascript:;" class="viewPages" data-filter="notices" >
                <div class="audit-stats-box blue">
                    <figure>
                        <img src="{{URL::asset('public/vendor/internal-pages/images/notices-icon.png')}}">
                    </figure>
                    <h3>{{ array_sum($errorsListing['notices']) }} <small>Notices</small></h3>
                    <i class="fa fa-long-arrow-right"></i>
                    @if($previousTask <> null)
                    <?php 
                    $noticesSum = (array_sum($previousTask['notices'])) - (array_sum($errorsListing['notices']));
                    $noticeColor = '';
                    $noticeArrow = '';
                    if($noticesSum > 0){
                        $noticeColor = 'number red';
                        $noticeArrow = '<span uk-icon="icon: arrow-up"></span>';                        
                    }elseif($noticesSum < 0){
                        $noticeColor = 'number green'; 
                        $noticeArrow = '<span uk-icon="icon: arrow-down"></span>';    
                        $noticesSum =  str_replace('-', '', $noticesSum);                  
                    }
                    ?>
                    @if($noticeColor <> null)
                    <div class="{{ $noticeColor }}">
                        {!! $noticeArrow !!}
                        {!! $noticesSum <> 0 ? $noticesSum :''; !!}
                    </div>
                    @endif
                    @endif
                </div>
            </a>
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
                <p>Here is a list of all technical issues Agency Dashboard has found on the website. Start fix them step by step from the most critical errors to less important. When you finished fixing issues, recrawl the website to make sure Website Score is up.</p>
            </div>

            <?php
            $CriticalErrors =  $zerolErrors = $warningErrors = $noticesErrors = '';
            $issuebreakcount = 0;
            ?>
            <div class="tab-content ">
                <div class="uk-switcher auditIssuesContainer">
                    <!-- Tab 1 All Issues -->
                    <div>
                        <table>
                            <tbody>
                                @foreach($errorsListing['critical'] as $keyName => $valueName)
                                    
                                    @if($valueName > 0)

                                    @if($issuebreakcount == 15)

                                        <tr>
                                            <td colspan="4" class="pa-0">
                                                <div class="table-audit-collapseed" style="display: none;">
                                                    <table>
                                                        <tbody>
                                    @endif

                                    <?php 
                                        if(isset($previousTask) && ($previousTask['critical'][$keyName] > $valueName)){
                                            $keylebel = '<i class="icon ion-arrow-up-a"></i>';
                                            $keyValue = $previousTask['critical'][$keyName] - $valueName;

                                        }elseif(isset($previousTask) && ($previousTask['critical'][$keyName] < $valueName)){
                                           $keylebel = '<i class="icon ion-arrow-down-a"></i>';
                                           $keyValue = $previousTask['critical'][$keyName] - $valueName;
                                           $keyValue = str_replace('-', '', $keyValue);
                                       }else{
                                           $keylebel = '';
                                           $keyValue = '';
                                       }
                                       $pages = $valueName == 1 ? 'page' : 'pages';
                                       ?>
                                    <tr>
                                        <td class="issue-type critical">
                                            <a href="javascript:;" class="viewPages" data-filter="{{ $keyName }}" >{{ $auditLevel[$keyName] }} </a>
                                        </td>
                                        <td>{{ $valueName }} {{ $pages }} </td>
                                        <td> {!! $keylebel !!} {{ $keyValue }} </td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-border blue-btn-border sidedrawererror"
                                            data-type="critical" data-value="{{ $keyName }}"
                                            uk-toggle="target: #offcanvas-flip"><i class="fa fa-question-circle"
                                            aria-hidden="true"></i> How to fix</a>
                                        </td>
                                    </tr>
                                    <?php   $issuebreakcount++; 
                                    $CriticalErrors .= '<tr><td class="issue-type critical"><a href="javascript:;" class="viewPages" data-filter="'. $keyName .'" >' . trim($auditLevel[$keyName])  .'</a></td><td>'. $valueName .' '.$pages.'</td><td>'. $keylebel . $keyValue .'</td> <td> <a href="#" class="btn btn-sm btn-border blue-btn-border sidedrawererror" data-type="critical" data-value="'.$keyName.'" uk-toggle="target: #offcanvas-flip" ><i class="fa fa-question-circle" aria-hidden="true"></i> How to fix</a></td> </tr>';
                                    ?> 
                                    @else
                                        <?php
                                        $zeroValue  = ($valueName)?$valueName:'0';
                                        $zerolErrors .= '<tr><td class="issue-type zero-error">'. $auditLevel[$keyName] .'</td><td>'. $zeroValue .' pages</td><td></td> <td> <a href="#" class="btn btn-sm btn-border blue-btn-border sidedrawererror" data-type="critical" data-value="'.$keyName.'" uk-toggle="target: #offcanvas-flip" ><i class="fa fa-question-circle" aria-hidden="true"></i> How to fix</a></td> </tr>';
                                        ?>

                                    @endif
                                  
                                @endforeach 

                                @foreach($errorsListing['warning'] as $keyName => $valueName)
                                    

                                    @if($valueName > 0)
                                    @if($issuebreakcount == 15)

                                        <tr>
                                            <td colspan="4" class="pa-0">
                                                <div class="table-audit-collapseed" style="display: none;">
                                                    <table>
                                                        <tbody>
                                    @endif

                                        <?php 
                                        if(isset($previousTask) && ($previousTask['warning'][$keyName] > $valueName)){
                                            $keylebel = '<i class="icon ion-arrow-up-a"></i>';
                                            $keyValue = $previousTask['warning'][$keyName] - $valueName;

                                        }elseif(isset($previousTask) && ($previousTask['warning'][$keyName] < $valueName)){
                                           $keylebel = '<i class="icon ion-arrow-down-a"></i>';
                                           $keyValue = $previousTask['warning'][$keyName] - $valueName;
                                           $keyValue = str_replace('-', '', $keyValue);
                                       }else{
                                           $keylebel = '';
                                           $keyValue = '';
                                       }
                                       $pages = $valueName == 1 ? 'page' : 'pages';
                                       ?>
                                       <tr>
                                            <td class="issue-type warnings">
                                                <a href="javascript:;" class="viewPages" data-filter="{{ $keyName }}" >{{ $auditLevel[$keyName] }} </a> 
                                            </td>
                                            <td>{{ $valueName }} {{ $pages }} </td>
                                            <td>{!! $keylebel !!} {{ $keyValue }}</td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-border blue-btn-border sidedrawererror" data-type="warning" data-value="{{ $keyName }}" uk-toggle="target: #offcanvas-flip"><i class="fa fa-question-circle" aria-hidden="true"></i> How to fix</a>
                                            </td>
                                        
                                        </tr>
                                        <?php   $issuebreakcount++;
                                        $warningErrors .= '<tr><td class="issue-type warnings"><a href="javascript:;" class="viewPages" data-filter="'. $keyName .'">' . $auditLevel[$keyName]  .'</a></td><td>'.  $valueName .' '.$pages .'  </td><td>'. $keylebel . $keyValue .'</td> <td> <a href="#" class="btn btn-sm btn-border blue-btn-border sidedrawererror" data-type="warning" data-value="'. $keyName .'" uk-toggle="target: #offcanvas-flip" ><i class="fa fa-question-circle" aria-hidden="true"></i> How to fix</a></td> </tr>';
                                        ?>
                                    @else
                                        <?php
                                        $zeroValue  = ($valueName)?$valueName:'0';
                                        $zerolErrors .= '<tr><td class="issue-type zero-error">'. $auditLevel[$keyName] .'</td><td>'. $zeroValue .' pages</td><td></td> <td> <a href="#" class="btn btn-sm btn-border blue-btn-border sidedrawererror" data-type="warning" data-value="'. $keyName .'" uk-toggle="target: #offcanvas-flip" ><i class="fa fa-question-circle" aria-hidden="true"></i> How to fix</a></td> </tr>';
                                        ?>
                                    @endif
                                   
                                @endforeach

                                @foreach($errorsListing['notices'] as $keyName => $valueName)
                                    

                                    @if($valueName > 0)
                                    @if($issuebreakcount == 15)
                                    <tr>
                                        <td colspan="4" class="pa-0">
                                            <div class="table-audit-collapseed" style="display: none;">
                                                <table>
                                                    <tbody>
                                    @endif

                                    <?php 
                                    if(isset($previousTask) && ($previousTask['notices'][$keyName] > $valueName)){
                                        $keylebel = '<i class="icon ion-arrow-up-a"></i>';
                                        $keyValue = $previousTask['notices'][$keyName] - $valueName;

                                    }elseif(isset($previousTask) && ($previousTask['notices'][$keyName] < $valueName)){
                                       $keylebel = '<i class="icon ion-arrow-down-a"></i>';
                                       $keyValue = $previousTask['notices'][$keyName] - $valueName;
                                       $keyValue = str_replace('-', '', $keyValue);
                                   }else{
                                       $keylebel = '';
                                       $keyValue = '';
                                   }
                                   $pages = $valueName == 1 ? 'page' : 'pages';
                                   ?>
                                   <tr>
                                        <td class="issue-type notices">
                                            <a href="javascript:;" class="viewPages" data-filter="{{ $keyName }}" >{{ $auditLevel[$keyName] }} </a> 
                                        </td>
                                        <td>{{ $valueName }} {{ $pages }}</td>
                                        <td>{!! $keylebel !!} {{ $keyValue }}</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-border blue-btn-border sidedrawererror"
                                            data-type="notices" data-value="{{ $keyName }}"
                                            uk-toggle="target: #offcanvas-flip"><i class="fa fa-question-circle"
                                            aria-hidden="true"></i> How to fix</a>
                                        </td>
                                    </tr>
                                    <?php   $issuebreakcount++;
                                    $noticesErrors .= '<tr><td class="issue-type notices"><a href="javascript:;" class="viewPages" data-filter="'. $keyName .'">' . $auditLevel[$keyName]  .'</a></td><td>'. $valueName .' '.$pages .' </td><td>'. $keylebel . $keyValue .'</td> <td> <a href="#" class="btn btn-sm btn-border blue-btn-border sidedrawererror" data-type="notices" data-value="'. $keyName .'" uk-toggle="target: #offcanvas-flip" ><i class="fa fa-question-circle" aria-hidden="true"></i> How to fix</a></td> </tr>';
                                    ?>
                                    @else
                                    <?php
                                    $zeroValue  = ($valueName)?$valueName:'0';
                                    $zerolErrors .= '<tr><td class="issue-type zero-error">'. $auditLevel[$keyName] .'</td><td>'. $zeroValue .'  pages</td><td></td> <td> <a href="#" class="btn btn-sm btn-border blue-btn-border sidedrawererror" data-type="notices" data-value="'. $keyName .'" uk-toggle="target: #offcanvas-flip" ><i class="fa fa-question-circle" aria-hidden="true"></i> How to fix</a></td> </tr>';
                                    ?>
                                    @endif
                                    
                                @endforeach

                                {!! $zerolErrors !!}
                                
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                   
                            </tbody>
                        </table>
                        <div class="audit-box-foot">
                            <a href="javascript:void(0);" class="show-more-audit-issues"><span uk-icon="icon:triangle-down"></span> <span class="t">Show More</span></a>
                        </div>     
                    </div>
                    <!-- Tab 1 All Issues End -->

                    <!-- Tab 2 Critical -->
                    <div>
                        <table>
                            {!! $CriticalErrors !!}
                        </table>
                    </div>
                    <!-- Tab 2 Critical End -->

                    <!-- Tab 3 Warnings -->
                    <div>
                        <table>

                            {!! $warningErrors !!}

                        </table>
                    </div>
                    <!-- Tab 3 Warnings End -->

                    <!-- Tab 4 Notices -->
                    <div>
                        <table>

                            {!! $noticesErrors !!}


                        </table>
                    </div>
                    <!-- Tab 4 Notices End -->

                    <!-- Tab 5 Zero Issues -->
                    <div>
                        <table>
                            {!! $zerolErrors !!}
                        </table>

                    </div>
                    <!-- Tab 5 Zero Issues End -->
                </div>
            </div>
        </div>
    </div>
</div>


