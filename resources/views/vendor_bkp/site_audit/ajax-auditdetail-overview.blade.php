<div class="elem-flex">
    <div class="elem-start">
        <div class="circle-donut" style="width:208px;height:208px;">
            <div class="circle_inbox"><span class="percent_text">{{ (int)$summaryTask['onpage_score'] }}</span> of 100</div>
            <input type="hidden" class="summary-chart-data" value="{{ (int)$summaryTask['onpage_score'] }}">
            <canvas id="myChart" width="50" height="50"></canvas>
        </div>
      
        <div class="score-for">
            <h2><small>Page score for</small>{{ $summaryTask['url'] }}</h2>
        </div>
    </div>
</div>


<div class="row">
    <div class="custom-width uk-flex">
        <div class="audit-stats-box red">
            <figure>
                <img src="{{ URL::asset('public/vendor/internal-pages/images/criticals-icon.png') }}">
            </figure>
            <h3>{{ array_sum($errorsListing['critical']) }}
                <small>Criticals</small>
            </h3>
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
        <div class="audit-stats-box yellow">
            <figure>
                <img src="{{ URL::asset('public/vendor/internal-pages/images/warnings-icon.png') }}">
            </figure>
            <h3>{{ array_sum($errorsListing['warning']) }}
                <small>Warnings</small>
            </h3>
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
            <!-- <div class="number red">
                <span uk-icon="icon: arrow-down" class="uk-icon"></span>33
            </div> -->
        </div>
        <div class="elem-end">
            <article>
                <ul>
                    <li>
                        <div>Status Code</div>
                        <div class="text-success">{{ $summaryTask['status_code'].' OK' }} </div>
                    </li>
                    <li>
                        <div>Indexation</div>
                        <div class="text-success">Indexable</div>
                    </li>
                    <li>
                        <div>Page Size</div>
                        <div class="text-success">{{ $summaryTask['size']/1000 }} KB</div>
                    </li>
                </ul>
            </article>
        </div>
    </div>
</div>