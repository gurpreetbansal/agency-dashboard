<div class="project-table-cover positive-main-data">
    <div class="project-table-body">
        <table id="positive_alerts">
            <thead>
                <tr>
                    <th>
                        Time
                    </th>
                    <th>
                        Project Name
                    </th>
                    <th>
                        Ranking URL
                    </th>
                    <th>
                        Keyword
                    </th>
                    <th>
                        Search Vol.
                    </th>
                    <th>
                        Previous Rank
                    </th>
                    <th>
                        New Rank
                    </th>
                </tr>
            </thead>
            <tbody>
                @for($j=1; $j<=5; $j++)
                <tr>
                    <td class="ajax-loader">....</td>
                    <td class="ajax-loader">....</td>
                    <td class="ajax-loader">....</td>
                    <td class="ajax-loader">....</td>
                    <td class="ajax-loader">....</td>
                    <td class="ajax-loader">....</td>
                    <td class="ajax-loader">....</td>
                </tr>
                @endfor    
            </tbody>
        </table>
    </div>
    <div class="project-table-foot positive-alerts-foot" id="positive_alerts_foot">
        <div class="project-entries positive-alerts-text">
          <p>.....................</p>
      </div>
      <div class="pagination PositiveAlerts">
        <ul class="pagination" role="navigation">
           <li class="page-item disabled" aria-disabled="true" aria-label="« Previous">
            <span class="page-link" aria-hidden="true">....</span>
        </li>
        <li class="page-item  active">
            <a class="page-link" href="javascript:;">...</a>
        </li>
        <li class="page-item ">
            <a class="page-link" href="javascript:;">...</a>
        </li>
        <li class="page-item">
            <a class="page-link" href="javascript:;" rel="next" aria-label="Next »">.....</a>
        </li>
    </ul>    
</div>
</div>

<input type="hidden" id="alerts_positive_page" value="1" />
</div>