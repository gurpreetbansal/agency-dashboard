<div class="audit-white-box mb-40 pa-0" id="search_optimization">
    <div class="audit-box-head">
        <h2>
            <img src="{{ URL::asset('/public/vendor/internal-pages/images/search-optim-icon.png') }}" alt=""> Search
            optimization
        </h2>
    </div>
    <div class="audit-box-body">
        <div class="border-bottom py-4">
            <ul uk-accordion class="content-accr p-0 m-0">
                <li>
                    <div class="uk-accordion-title px-4 py-2">
                        <div class="uk-grid">
                            <div class="uk-width-1-4">
                                <h5 class="uk-text-light">Canonical link check</h5>
                            </div>
                            <div class="uk-width-3-4"><a href="{{ $summaryTask['meta']['canonical'] }}">{{ $summaryTask['meta']['canonical'] }} </a>
                            </div>
                        </div>
                    </div>
                    <div class="uk-accordion-content p-4 mt-0">
                        <p>We recommend to use a canonical URL to avoid issues with duplicate content. When Googlebot
                            indexes a site, it tries to determine the primary content of each page. If Googlebot finds
                            multiple pages on the same site that seem to be the same, it chooses the page that it thinks
                            is the most complete and useful, and marks it as canonical. The canonical page will be
                            crawled most regularly; duplicates are crawled less frequently in order to reduce Google
                            crawling load on your site.

                            Google chooses the canonical page based on a number of factors (or signals), such as whether
                            the page is served via http or https; page quality; presence of the URL in a sitemap; and
                            any rel=canonical labeling. You can indicate your preference to Google using these
                            techniques, but Google may choose a different page as canonical than you do, for various
                            reasons.

                            Use this <a href="https://developers.google.com/search/docs/advanced/crawling/consolidate-duplicate-urls" target="_blank">Google guide</a> article to learn more on how to create and manage canonical
                        tags.</p>
                    </div>
                </li>
            </ul>

            <div class="px-4 py-2">
                <div class="uk-grid">
                    <div class="uk-width-1-4"><img
                        src="{{ URL::asset('/public/vendor/internal-pages/images/images-icon.png') }}"
                        class="sub-2 mr-1" alt="" /> Status Code</div>
                        <div class="uk-width-3-4 text-success">200 OK</div>
                    </div>
                </div>
            </div>

            <div class="border-bottom py-4">
                <h5 class="uk-text-light px-4 mb-0 py-1 ">Alternate link check</h5>
                <ul uk-accordion class="content-accr p-0 m-0 ">
                    <li>
                        <div class="uk-accordion-title px-4 py-2 ">
                            <div class="uk-grid ">
                                <div class="uk-width-1-4"><img
                                    src="{{ URL::asset('/public/vendor/internal-pages/images/images-icon.png') }}"
                                    class="sub-2 mr-1 " alt=" " /> Hreflang tags</div>
                                    <div class="uk-width-3-4">Hreflang tags not found</div>
                                </div>
                            </div>
                            <div class="uk-accordion-content p-4 mt-0 ">
                                <p>If you have multiple versions of a page for different languages or regions, tell Google about these different variations. Doing so will help Google Search point users to the most appropriate version of your page by language or region.

                                    Note that even without taking action, Google might still find alternate language versions of your page, but it is usually best for you to explicitly indicate your language- or region-specific pages.

                                Use Google guide and our article to learn more on how to use hreflang tags.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="audit-white-box mb-40 pa-0" id="images">
            <div class="audit-box-head">
                <h2><img src="{{ URL::asset('/public/vendor/internal-pages/images/images-icon.png') }}" alt=""> Images </h2>
            </div>
            <div class="audit-box-body">
                <div class="border-bottom py-4">
                    <h5 class="uk-text-light px-4 mb-0 py-1 ">Favicon</h5>
                    <ul uk-accordion class="content-accr p-0 m-0 ">
                        <li>
                            <div class="uk-accordion-title px-4 py-2 ">
                                <div class="uk-grid ">
                                    <div class="uk-width-1-4">
                                        @if($summaryTask['checks']['no_favicon'] == false)
                                        <img src="{{ URL::asset('/public/vendor/internal-pages/images/check-icon.png') }} "
                                        class="sub-2 mr-1 " alt=" " />
                                        @else
                                        <img src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }} "
                                        class="sub-2 mr-1 " alt=" " />
                                        @endif
                                        Favicon
                                    </div>
                                    <div class="uk-width-3-4"> 
                                         <img src="{{ $summaryTask['meta']['favicon'] }} " class="sub-2 mr-1 " alt="favicon" type="image/x-icon" />
                                    </div>
                                </div>
                            </div>
                            <div class="uk-accordion-content p-4 mt-0 ">
                                <p>Check if your site is using and correctly implementing a favicon. Favicons are small
                                    icons that appear in your browser's URL navigation bar.

                                    They are also saved next to your URL's title when your page is bookmarked. This
                                    helps
                                    brand your site and make it easy for users to navigate to your site among a list of
                                    bookmarks.

                                    Use Google guide to find out how to create and use favicons.
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="border-bottom py-4">
                    <h5 class="uk-text-light px-4 mb-0 py-1 ">
                        Images <span class="text-secondary">(found {{ count($images['items']) }})</span>
                    </h5>
                    <div class="px-4 py-2 show-more-audit-imgmore">
                        <style>
                            span.preview-img {
                                width: 150px;
                            }
                        </style>
                        <div class="table-responsive">
                            <table class="uk-table mt-0 mb-0 uk-table-middle">
                                <thead>
                                    <tr class="border bg-grey uk-border-rounded ">
                                        <th>Preview</th>
                                        <th>Alt Attribute </th>
                                        <th>Title Attribute </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($images['items'] as $key =>$imagvlaue)
                                    <tr>
                                        <td><a href="{{ $imagvlaue['image_src'] }}" target="_blank"><span class="preview-img"><img src="{{ $imagvlaue['image_src'] }}" alt="" /></span></a></td>
                                        @if($imagvlaue['image_alt'] <> null)
                                        <td>{{$imagvlaue['image_alt']}}</td>
                                        @else
                                        <td class="text-danger">{{ '[Missed]' }}</td>
                                        @endif

                                        @if($imagvlaue['text'] <> null)
                                        <td>{{$imagvlaue['text']}}</td>
                                        @else
                                        <td class="text-danger">{{ '[Missed]' }}
                                        @endif
                                        </td>
                                    </tr>
                                    @if($key == 5)
                                </tbody>
                                <tbody class="table-audit-collapseed" style="display: none;">
                                    @endif
                                    @endforeach


                                </tbody>
                            </table>
                        </div>
                        <div class="audit-box-foot">
                            <a href="javascript:void(0);" class="show-more-audit-img"><span  uk-icon="icon:triangle-down"></span> <span class="t">Show More</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="audit-white-box mb-40 pa-0" id="links">
            <div class="audit-box-head">
                <h2><img src="{{ URL::asset('/public/vendor/internal-pages/images/link-icon.png') }}" alt=""> Links</h2>
            </div>

            <div class="audit-box-body">
                <div class="border-bottom py-4 show-more-audit-extlinksexpand">
                    <h5 class="uk-text-light px-4 mb-0 py-1 ">
                        External Links <span class="text-secondary">(found {{ count($externalLinks['items']) }})</span>
                    </h5>
                    <div class="px-4 py-2">
                        <div class="table-responsive">
                            <table class="uk-table mb-0 uk-table-middle">
                                <thead>
                                    <tr class="border bg-grey uk-border-rounded">
                                        <th>#</th>
                                        <th>Link </th>
                                        <th>Anchor </th>
                                        <th>Code</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($externalLinks['items'] as $key =>$imagvlaue)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>
                                            @if($imagvlaue['link_to'] <> null)
                                            <a href="{{$imagvlaue['link_to']}}" target="_blank">
                                                {{substr($imagvlaue['link_to'], 0, 100) .((strlen($imagvlaue['link_to']) > 100) ? ' ...' : '')}}
                                            </a>
                                            @else
                                            {{'Missed'}}
                                            @endif
                                        </td>
                                        <td>{{ $imagvlaue['type']  }}</td>
                                        <td class="text-success">200 OK</td>
                                    </tr>
                                    @if($key == 5)
                                </tbody>
                                <tbody class="table-audit-collapseed" style="display: none;">
                                    @endif
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div class="audit-box-foot">
                            <a href="javascript:void(0);" class="show-more-audit-extlinks"><span  uk-icon="icon:triangle-down"></span> <span class="t">Show More</span></a>
                        </div>
                    </div>
                </div>
                <div class="border-bottom py-4 show-more-audit-intlinksexpand">
                    <h5 class="uk-text-light px-4 mb-0 py-1 ">  Internal Links <span class="text-secondary">(found {{ count($internalLinks['items']) }})</span>
                    </h5>
                    <div class="px-4 py-2">
                        <table class="uk-table mb-0 uk-table-middle">
                            <thead>
                                <tr class="border bg-grey uk-border-rounded">
                                    <th>#</th>
                                    <th>Link </th>
                                    <th>Anchor </th>
                                    <th>Code</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($internalLinks['items'] as $key =>$imagvlaue)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>
                                        @if($imagvlaue['link_to'] <> null)
                                        <a href="{{$imagvlaue['link_to']}}" target="_blank">
                                            {{substr($imagvlaue['link_to'], 0, 100) .((strlen($imagvlaue['link_to']) > 100) ? ' ...' : '')}}
                                        </a>
                                        @else
                                        {{'Missed'}}
                                        @endif
                                    </td>
                                    <td>{{ $imagvlaue['type']  }}</td>
                                    <td class="text-success">200 OK</td>
                                </tr>
                                @if($key == 5)
                            </tbody>
                            <tbody class="table-audit-collapseed" style="display: none;">
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                        <div class="audit-box-foot">
                            <a href="javascript:void(0);" class="show-more-audit-intlinks"><span  uk-icon="icon:triangle-down"></span> <span class="t">Show More</span></a>
                        </div>
                    </div>
                </div>
                <!-- <div class="py-4 ">
                    <h5 class="uk-text-light px-4 mb-0 py-1 ">
                        Subdomain Links <span class="text-secondary">(not found)</span>
                    </h5>
                </div> -->
            </div>
        </div>