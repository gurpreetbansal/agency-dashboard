<!--Organic Keyword Growth Row -->
<div class="white-box pa-0 mb-40 space-top noBreakBefore">
 <div class="box-boxshadow">
  <div class="section-head">
    <h4>
      <figure><img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}"></figure> Organic Keyword Growth
      <font class="organic_keyword_time"></font>
    </h4>
    <hr />
    <p>
      The distribution of the domain's organic ranking over time. You can see how many keywords have rankings in Google's top 3, top 10, top 20, and top 100 organic search results.
    </p>
    <div uk-grid class="section-divide">
      <div class="uk-width-1-2">
        <ul>
          <li><b class="uk-text-medium"><img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}" alt="">Top 3 Positions</b> <span class="organic_keyword_top3"></span></li>
          <li><b class="uk-text-medium"><img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}" alt="">Position 4-10</b> <span class="organic_keyword_4_10"></span></li>
          <li><b class="uk-text-medium"><img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}" alt="">Position 11-20</b> <span class="organic_keyword_11_20"></span> </li>
        </ul>
      </div>
      <div class="uk-width-1-2">
        <ul>
          <li><b class="uk-text-medium"><img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}" alt="">Position 21-50</b> <span class="organic_keyword_21_50"></span></li>
          <li><b class="uk-text-medium"><img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}" alt="">Position 51-100</b> <span class="organic_keyword_51_100"></span></li>
          <li><b class="uk-text-medium"><img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}" alt="">Total Keywords</b> <span class="organic_keyword_total"></span></li>
        </ul>
      </div>
    </div>
  </div>

  <div class="white-box-body less-space">
    <div class="chart h-345">
      <canvas id="new-keywordsCanvas" width="50" height="40" > </canvas>
    </div>
  </div>
</div>
</div>
<!--Organic Keyword Growth Row End -->
<div class="table-box">
  <div class="white-box space-top pa-0 mb-40">
    <div class="box-boxshadow">
     <div class="section-head">
      <h4>
        <figure><img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}"></figure>
        Top Organic Keywords : Top <small class="total_count">0</small> keywords your website is ranking for
      </h4>
      <hr />
      <p>
        <small><em><img src="{{URL::asset('public/vendor/internal-pages/images/info.png')}}"> By default, we show upto 700 keywords</em></small>
      </p>
    </div>
    <div class="white-box-body">
      <div class="top-organic-keyword-table">
        <div class="table-responsive">
          <table class="style1" id="extra-organix">
            <thead>
              <tr>
                <th>Keyword</th>
                <th>Pos.</th>
                <th>Volume </th>
                <th>CPC (USD)</th>
                <th>Traffic %</th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
        </div>
      </div>
      <div class="uk-text-center pa-20">
        <p class="mb-0"><a href="{{url('/project-detail/'.$share_key)}}" class="btn blue-btn">To view more Click here <i class="fa fa-external-link"></i></a></p>
      </div>

    </div>
  </div>
</div>
</div>
<!--Organic Keyword Growth Row End -->