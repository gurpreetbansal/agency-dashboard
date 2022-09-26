<div class="audit-box-head">
    <h2>Page-level issues 
        <span uk-tooltip="title: It displays a comprehensive list of technical issues spotted on different pages of your site.; pos: top-left" class="fa fa-info-circle"></span> 
    </h2>
</div>
<div class="audit-box-body">
    <div class="audit-stats">
        <a href="{{ url('/audit-pages/'.$campaign_id.'?filter=critical') }}">
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
        <a href="{{ url('/audit-pages/'.$campaign_id.'?filter=warning') }}">
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
        <a href="{{ url('/audit-pages/'.$campaign_id.'?filter=notices') }}">
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
                                        <a href="{{ url('/audit-pages/'.$campaign_id.'?filter='.$keyName) }}">{{ $auditLevel[$keyName] }} </a>
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
                                $CriticalErrors .= '<tr><td class="issue-type critical"><a href="'. url('/audit-pages/'.$campaign_id.'?filter='.$keyName) .'">' . trim($auditLevel[$keyName])  .'</a></td><td>'. $valueName .' '.$pages.'</td><td>'. $keylebel . $keyValue .'</td> <td> <a href="#" class="btn btn-sm btn-border blue-btn-border sidedrawererror" data-type="critical" data-value="'.$keyName.'" uk-toggle="target: #offcanvas-flip" ><i class="fa fa-question-circle" aria-hidden="true"></i> How to fix</a></td> </tr>';
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
                                            <a href="{{ url('/audit-pages/'.$campaign_id.'?filter='.$keyName) }}">{{ $auditLevel[$keyName] }} </a> 
                                        </td>
                                        <td>{{ $valueName }} {{ $pages }} </td>
                                        <td>{!! $keylebel !!} {{ $keyValue }}</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-border blue-btn-border sidedrawererror" data-type="warning" data-value="{{ $keyName }}" uk-toggle="target: #offcanvas-flip"><i class="fa fa-question-circle" aria-hidden="true"></i> How to fix</a>
                                        </td>
                                    
                                    </tr>
                                    <?php   $issuebreakcount++;
                                    $warningErrors .= '<tr><td class="issue-type warnings"><a href="'. url('/audit-pages/'.$campaign_id.'?filter='.$keyName) .'">' . $auditLevel[$keyName]  .'</a></td><td>'.  $valueName .' '.$pages .'  </td><td>'. $keylebel . $keyValue .'</td> <td> <a href="#" class="btn btn-sm btn-border blue-btn-border sidedrawererror" data-type="warning" data-value="'. $keyName .'" uk-toggle="target: #offcanvas-flip" ><i class="fa fa-question-circle" aria-hidden="true"></i> How to fix</a></td> </tr>';
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
                                    <td class="issue-type notices"><a
                                        href="{{ url('/audit-pages/'.$campaign_id.'?filter='.$keyName) }}">{{ $auditLevel[$keyName] }}
                                    </a> </td>
                                    <td>{{ $valueName }} {{ $pages }} </td>
                                    <td>{!! $keylebel !!} {{ $keyValue }}</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-border blue-btn-border sidedrawererror"
                                        data-type="notices" data-value="{{ $keyName }}"
                                        uk-toggle="target: #offcanvas-flip"><i class="fa fa-question-circle"
                                        aria-hidden="true"></i> How to fix</a>
                                    </td>
                                </tr>
                                <?php   $issuebreakcount++;
                                $noticesErrors .= '<tr><td class="issue-type notices"><a href="'. url('/audit-pages/'.$campaign_id.'?filter='.$keyName) .'">' . $auditLevel[$keyName]  .'</a></td><td>'. $valueName .' '.$pages .' </td><td>'. $keylebel . $keyValue .'</td> <td> <a href="#" class="btn btn-sm btn-border blue-btn-border sidedrawererror" data-type="notices" data-value="'. $keyName .'" uk-toggle="target: #offcanvas-flip" ><i class="fa fa-question-circle" aria-hidden="true"></i> How to fix</a></td> </tr>';
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



