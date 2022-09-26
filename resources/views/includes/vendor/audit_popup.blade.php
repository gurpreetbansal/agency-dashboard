<style>.highlightClass {
  background-color:#FFFF00;
}
.highlightIssue {
  background-color:#96a1a940;
}
</style>

<div class="popup" data-pd-popup="howisitcalculated">
    <div class="popup-inner">
        <a class="popup-close" data-pd-popup-close="howisitcalculated" href="#"></a>
        <div class="popup-innerContent">
            <div class="uk-flex">
                <div>
                    <h2>How is it calculated?</h2>
                    <h4>How is Website Score calculated</h4>
                    <p>We use following formula to calculate this metric.</p>
                </div>
                <figure><img src="{{URL::asset('public/vendor/internal-pages/images/calculated-vector.png')}}" alt="calculated-vector"></figure>
            </div>            
            <ul>
                <li>
                    <strong>
                        <figure><img src="{{URL::asset('public/vendor/internal-pages/images/calculated-icon1.png')}}" alt="calculated-icon1"></figure>
                        Website Score =
                    </strong> 
                    (sum OnePageScore) / # of pages
                </li>
                <li>
                    <strong>
                        <figure><img src="{{URL::asset('public/vendor/internal-pages/images/calculated-icon2.png')}}" alt="calculated-icon2"></figure>
                        OnePageScore =
                    </strong> 
                    100 - cost of critical error one - cost of critical error two - cost of warning one ...
                </li>
                <li>
                    <strong>
                        <figure><img src="{{URL::asset('public/vendor/internal-pages/images/calculated-icon3.png')}}" alt="calculated-icon3"></figure>
                        Cost of specific critical error =
                    </strong> 
                    (60 * # of specific errors) / # of all critical errors
                </li>
                <li>
                    <strong>
                        <figure><img src="{{URL::asset('public/vendor/internal-pages/images/calculated-icon4.png')}}" alt="calculated-icon4"></figure>
                        Cost of specific warning =
                    </strong> 
                    (40 * # of specific warnings) / # of all warnings
                </li>
            </ul>
            <p> Let's dive into each element of formula.</p>
            <p>Website Score is an average score across all website pages. By default each page has 100 points. And we minus
                from 100 points cost of each error. </p>
            <p>Cost of error isn't static for all websites. It is calculated for each new crawl and depends from 2 factors:
                type of error (critical or warning) and how often a specific error occurs on the site. Critical errors take
                more points than warnings. Popular errors take more points than rare errors. Notices have no impact on
                Website Score.</p>
            <p>We use index 60 for critical errors and index 40 for warnings to give more weight critical errors.</p>
        </div>
    </div>
</div>

<div id="offcanvas-flip" uk-offcanvas="flip: true; overlay: true">
    <div class="uk-offcanvas-bar custom-offcanvas">
        <div class="progress-loader"></div>
        <button class="uk-offcanvas-close" type="button" uk-close></button>
        <div class="gbox red-gradient">
            <h3><small>Critical</small> <span class="sidedrawer-label"> 4xx client errors </span> </h3>
        </div>
        <div class="content-box">
            <div class="sidedrawer-short-description">
                <p>Below you see the list of URLs with 4xx status code. Sitechecker bot found these URLs
                    because
                    other pages on your website link to them. To check which pages contain the specific
                    broken
                    link, click “Anchors.”
                </p>
            </div>
            <hr />
            <div class="sidedrawer-description">
                <h5>Why It's Important</h5>
                <p>A 4xx error deserves maximum attention.</p>
                <p>
                    Such error signals that the content of the page isn’t visible to search engines, which
                    also
                    means that the page won’t be displayed in search engine results - this will impact
                    organic
                    traffic to the page. Importantly, if a 4xx error is detected by search engines, the
                    respective page would be removed from their index and it might be troublesome to get it
                    re-indexed once the problem is solved. If multiple 4xx errors are detected on your site,
                    search engines might even lower its ranking or the number of pages indexed.
                </p>
            </div>
        </div>

    </div>
</div>

<div id="offcanvas-pagecode" uk-offcanvas="flip: true; overlay: true">
    <div class="uk-offcanvas-bar custom-offcanvas viewsource-side">
        <button class="uk-offcanvas-close" type="button" uk-close></button>
        <div class="gbox gray-gradient source-label">
            <h3><small>Critical</small> <span class="sidedrawer-label"> https://example.com </span> </h3>
        </div>
        <div class="progress-loader"> </div>
        <div class="content-box pagecode">
            <div class="search-overlay">
                <div class="searchBox ">
                    <a href="javascript:void(0)" id="openSearch"><span uk-icon="icon: search"></span></a>
                    <a href="javascript:;" class="auditDrawer-search-clear" style="display: none;">
                        <span class="clear-input auditDrawerClear uk-icon" uk-icon="icon: close;"></span>
                    </a>
                    <div class="search-field">
                        <!-- <form> -->
                            <input type="text" placeholder="Search" autofocus class="search-in-content">
                        <!-- </form> -->
                    </div>
                </div>                
                <a href="javascript:;" id="copy-textarea-content"><span uk-icon="icon: copy"></span></a>
            </div>
            <textarea id="code" class="textarea-content"></textarea>
        </div>
    </div>
</div>

<div id="offcanvas-issueincode" uk-offcanvas="flip: true; overlay: true">
    <div class="uk-offcanvas-bar custom-offcanvas issue-side">
        <button class="uk-offcanvas-close" type="button" uk-close></button>
        <div class="gbox gray-gradient source-label">
            <h3><small>Critical</small> <span class="sidedrawer-label"> https://example.com </span> </h3>
        </div>        
        <div class="content-box">
	        <p>The title on some pages is shorter than 35 characters. </p>
	        <h5>The issue in the URL's source code</h5>
	        <p>The title on some pages is shorter than 35 characters. </p>

	        <div class="progress-loader"> </div>
	        <textarea id="codeissue" class="textarea-content"></textarea>

	        <p>You can check a source code with the help of online tools like this one <a href="#">https://codebeautify.org/source-code-viewer</a> or by using your browser commands. For instance, in the Chrome browser, right-click on a blank region and pick the option “View page source.” </p>
        </div>
    </div>
</div>
