<div class="elem-flex">
    <div class="elem-start">
        <div class="circle-donut" style="width:208px;height:208px;">
            <div class="circle_inbox"><span class="percent_text">{{ (int)$summaryTask['onpage_score'] }}</span> of 100</div>
            <input type="hidden" class="summary-chart-data" value="{{ (int)$summaryTask['onpage_score'] }}">
            <canvas id="myChart" width="50" height="50"></canvas>
      </div>
       <!--  <div class="circle_percent" data-percent="{{ $summaryTask['onpage_score'] }}">
            <div class="circle_inner">
                <div class="round_per">
                </div>
            </div>
            <div class="circle_inbox"><span class="percent_text">{{ $summaryTask['onpage_score'] }}</span>of 100
            </div>
        </div> -->

        <div class="score-for">
            <h2><small>Page score for</small>{{ $summaryTask['url'] }}</h2>
            <!-- <p><a href="#">How is it calculated?</a></p>
            <ul>
                <li>IP: {{ $summaryTaskOverView['domain_info']['ip'] }}</li>
                <li>|</li>
                <li>SSL: {{ $summaryTaskOverView['domain_info']['checks']['ssl'] == 1 ? "enabled": "N/A" }}</li>
            </ul> -->
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
            <div class="number red">
                <span uk-icon="icon: arrow-down" class="uk-icon"></span>1
            </div>
        </div>
        <div class="audit-stats-box yellow">
            <figure>
                <img src="{{ URL::asset('public/vendor/internal-pages/images/warnings-icon.png') }}">
            </figure>
            <h3>{{ array_sum($errorsListing['warning']) }}
                <small>Warnings</small>
            </h3>
            <div class="number red">
                <span uk-icon="icon: arrow-down" class="uk-icon"></span>33
            </div>
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