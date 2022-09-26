<!--Organic Keyword Growth Row -->
<div class="white-box pa-0 mb-40">
  <div class="white-box-head">
    <!-- <span class="white-box-handle" uk-icon="icon: table"></span> -->
    <div class="left">
      <div class="heading">
        <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
        <div>
          <h2>Organic Keyword Growth
            <span uk-tooltip="title: This section shows growth in organic keywords month after month, We show total keywords in Top3, Top 10, Top 20, Top 30 and Top 100 that your domain is ranking for.; pos: top-left" class="fa fa-info-circle"></span>
          </h2>
          <p class="organic_keyword_time"></p>
        </div>
      </div>
    </div>
    <div class="right">
        <div class="loader h-33 half-px"></div>
        <div class="filter-list">
          <ul>
            <li>
               <a href="javascript:;" data-request-id="{{@$campaign_id}}" id="refresh_organic_keyword_growth" class="btn icon-btn color-purple" uk-tooltip="title: Refresh Organic Keyword Growth; pos: top-center" title="" aria-expanded="false">
                  <img src="{{URL::asset('/public/vendor/internal-pages/images/refresh-icon.png')}}">
                </a>
            </li>
          </ul>
        </div>
      </div>
  </div>

  <div class="white-box-body">
    <div uk-grid>
      <div class="uk-width-1-2@l uk-width-1-1@m">
        <div id="keywords-canvas" class="chart h-345 ajax-loader">
          <canvas id="new-keywordsCanvas" width="50" height="40" > </canvas>
        </div>
      </div>
      <div class="uk-width-1-2@l uk-width-1-1@m">
        <div class="top-organic-keyword-table">
          <div class="top-organic-keyword-table-head">
            <h6 class="top-key-organic ajax-loader">Top Organic Keywords <small class="total_count"></small></h6>
            <a href="{{url('extra-organic-keywords/'.$campaign_id)}}" target="_blank" class="btn blue-btn btn-sm top-key-organic ajax-loader">View details</a>
          </div>
          <div class="table-responsive">
            <table class="style1" id="extra_organic_keywords">
              <thead>
                <tr>
                  <th class="ajax-loader">Keyword</th>
                  <th class="ajax-loader">Pos.</th>
                  <th class="ajax-loader">Volume </th>
                  <th class="ajax-loader">CPC (USD)</th>
                  <th class="ajax-loader">Traffic %</th>
                </tr>
              </thead>
              <tbody >
                <tr>
                  <td class="ajax-loader">....</td>
                  <td class="ajax-loader">....</td>
                  <td class="ajax-loader">....</td>
                  <td class="ajax-loader">....</td>
                  <td class="ajax-loader">....</td>
                </tr>
                <tr>
                  <td class="ajax-loader">....</td>
                  <td class="ajax-loader">....</td>
                  <td class="ajax-loader">....</td>
                  <td class="ajax-loader">....</td>
                  <td class="ajax-loader">....</td>
                </tr>
                <tr>
                  <td class="ajax-loader">....</td>
                  <td class="ajax-loader">....</td>
                  <td class="ajax-loader">....</td>
                  <td class="ajax-loader">....</td>
                  <td class="ajax-loader">....</td>
                </tr>

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--Organic Keyword Growth Row End -->