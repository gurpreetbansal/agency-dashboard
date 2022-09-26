<div class="audit-white-box mb-40 pa-0" id="issues_overview">
    <div class="audit-box-head">
        <h2><img src="{{ URL::asset('public/vendor/internal-pages/images/issue-overview-icon.png') }}" alt="" /> Issues
        overview</h2>
    </div>
    <div class="audit-box-body">
        @if(count($errorsListing['critical']) > 0)
        <div class="border-bottom py-4">
            <h5 class="uk-text-light px-4 mb-0 py-1">Criticals</h5>

            @foreach($errorsListing['critical'] as $keyName => $valueName)
            <div class="px-4 py-2">
                <div class="uk-grid">
                    <div class="uk-width-2-4"><img src="{{ URL::asset('public/vendor/internal-pages/images/cross-icon.png') }}"
                        class="sub-2 mr-1" alt="" /> {{ $auditLevel[$keyName] }}</div>
                    <div class="uk-width-2-4"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
        @if(count($errorsListing['warning']) > 0)
        <div class="py-4">
            <h5 class="uk-text-light px-4 mb-0 py-1">Warnings</h5>

            @foreach($errorsListing['warning'] as $keyName => $valueName)
            <div class="px-4 py-2">
                <div class="uk-grid">
                    <div class="uk-width-2-4"><img
                        src="{{ URL::asset('public/vendor/internal-pages/images/warning-icon.png') }}"
                        class="sub-2 mr-1" alt="" /> {{ $auditLevel[$keyName] }}</div>
                        <div class="uk-width-2-4"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

<div class="audit-white-box mb-40 pa-0" id="content_optimization">
    <div class="audit-box-head">
        <h2><img src="{{ URL::asset('public/vendor/internal-pages/images/content-opti-icon.png') }}" alt="" /> Content
        optimization</h2>
    </div>
    <div class="audit-box-body">
        <div class="border-bottom py-4">
            <h5 class="uk-text-light px-4 mb-0 py-1">General</h5>
            <ul uk-accordion class="content-accr p-0 m-0">
                <li>
                    <div class="uk-accordion-title px-4 py-2">
                        <div class="uk-grid">
                            <div class="uk-width-1-4"><img src="{{ URL::asset('public/vendor/internal-pages/images/check-icon.png') }}" class="sub-2 mr-1" alt="" /> Status code</div>
                            <div class="uk-width-3-4 text-success">{{ $summaryTask['status_code'].' OK' }}
                            </div>
                        </div>
                    </div>
                    <div class="uk-accordion-content p-4 mt-0">
                        <p>HTTP response status codes indicate whether a specific HTTP request has been
                        successfully completed. Responses are grouped into five classes:</p>
                        <ol>
                            <li>Informational responses (100–199)</li>
                            <li>Successful responses (200–299)</li>
                            <li>Redirects (300–399)</li>
                            <li>Client errors (400–499)</li>
                            <li>Server errors (500–599)</li>
                        </ol>

                    </div>
                </li>
                <li>
                    <div class="uk-accordion-title px-4 py-2">
                        <div class="uk-grid">
                            <div class="uk-width-1-4"><img src="{{ URL::asset('public/vendor/internal-pages/images/check-icon.png') }}"  class="sub-2 mr-1" alt="" /> HTML Size</div>
                            <div class="uk-width-3-4">{{ $summaryTask['size']/1000 }} KB</div>
                        </div>
                    </div>
                    <div class="uk-accordion-content p-4 mt-0">
                        <p>This is the size of all HTML code on the web page, except external JavaScript or
                            external CSS files. A page of big size can lead to slow response time and scare
                            off
                        users. You should keep your web page size below 2 Mb.</p>
                    </div>
                </li>
            </ul>
        </div>

        <div class="border-bottom py-4">
            <ul uk-accordion class="content-accr p-0 m-0">
                <li>
                    <div class="uk-accordion-title px-4 py-2">
                        <div class="uk-grid">
                            <div class="uk-width-1-4">
                                <h5 class="uk-text-light">Title check</h5>
                            </div>
                            <div class="uk-width-3-4">{{ $summaryTask['meta']['title'] }}</div>
                        </div>
                    </div>
                    <div class="uk-accordion-content p-4 mt-0">
                        <p>The meta title is an HTML tag that defines the title of your page. This tag
                            displays
                            your page title in search engine results, at the top of a user's browser, and
                            also
                            when your page is bookmarked in a list of favorites.

                            Titles are critical to giving users a quick insight into the content of a result
                            and
                            why it's relevant to their query. It's often the primary piece of information
                            used
                            to decide which result to click on, so it's important to use high-quality titles
                            on
                            your web pages.

                            Avoid too short and too long or verbose titles, which are likely to get
                            truncated
                            when they show up in the search results. Learn more on how to create good title
                            tags
                            using <a target="_blank" href="https://developers.google.com/search/docs/advanced/appearance/good-titles-snippets#create-descriptive-page-titles">Google guide</a>. 
                        </p>
                    </div>
                </li>
            </ul>

            <div class="px-4 py-2">
                <div class="uk-grid">
                    <div class="uk-width-1-4">
                        @if($summaryTask['meta']['title_length'] == 0)
                        <img  src="{{ URL::asset('public/vendor/internal-pages/images/cross-icon.png') }}" class="sub-2 mr-1" alt="" />
                        @elseif($summaryTask['meta']['title_length'] > 35)
                        <img  src="{{ URL::asset('public/vendor/internal-pages/images/check-icon.png') }}" class="sub-2 mr-1" alt="" />
                        @elseif($summaryTask['meta']['title_length'] < 35)
                        <img  src="{{ URL::asset('public/vendor/internal-pages/images/warning-icon.png') }}" class="sub-2 mr-1" alt="" />
                       
                        @endif
                        Title length
                    </div>
                    <div class="uk-width-3-4">{{ $summaryTask['meta']['title_length'] }} characters (Recommended:
                    35-65 characters)</div>
                </div>
            </div>
        </div>

        <div class="border-bottom py-4">
            <ul uk-accordion class="content-accr p-0 m-0">
                <li>
                    <div class="uk-accordion-title px-4 py-2">
                        <div class="uk-grid">
                            <div class="uk-width-1-4">
                                <h5 class="uk-text-light">Description check</h5>
                            </div>
                            <div class="uk-width-3-4">{{ $summaryTask['meta']['description'] }}
                            </div>
                        </div>
                    </div>
                    <div class="uk-accordion-content p-4 mt-0">
                        <p>Google will sometimes use the description tag from a page to generate a search
                            results snippet. A meta description tag should generally inform and interest
                            users
                            with a short, relevant summary of what a particular page is about. This is like
                            a
                            pitch that convince users that the page is exactly what they're looking for.

                            Learn more on how to create a good meta description tag, using this <a href="https://developers.google.com/search/docs/advanced/appearance/good-titles-snippets#create-good-meta-descriptions"
                            target="_blank">Google guide</a>.
                        </p>
                    </div>
                </li>
            </ul>

            <div class="px-4 py-2">
                <div class="uk-grid">
                    <div class="uk-width-1-4">
                        @if($summaryTask['meta']['description_length'] == 0)
                        <img  src="{{ URL::asset('public/vendor/internal-pages/images/cross-icon.png') }}" class="sub-2 mr-1" alt="" />
                        @elseif($summaryTask['meta']['description_length'] > 35)
                        <img  src="{{ URL::asset('public/vendor/internal-pages/images/check-icon.png') }}" class="sub-2 mr-1" alt="" />
                        @elseif($summaryTask['meta']['description_length'] < 35)
                        <img  src="{{ URL::asset('public/vendor/internal-pages/images/warning-icon.png') }}" class="sub-2 mr-1" alt="" />

                        @endif
                    Description Length</div>
                    <div class="uk-width-3-4"><b
                        class="uk-text-semi-bold"><?php 
                        if($summaryTask['meta']['description_length'] > 0){
                            echo $summaryTask['meta']['description_length'].' characters (Recomended: 70-320 characters)';
                        }elseif($summaryTask['meta']['description_length'] == 0){
                            echo "Description tag not found";
                        }
                        ?></b>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-bottom py-4">
            <ul uk-accordion class="content-accr p-0 m-0">
                <li>
                    <div class="uk-accordion-title px-4 py-2">
                        <div class="uk-grid">
                            <div class="uk-width-1-1">
                                <h5 class="uk-text-light">Google preview</h5>
                            </div>
                        </div>
                    </div>
                    <div class="uk-accordion-content p-4 mt-0">
                        <p>Check how your page might appear in Google search results. Google search results
                            typically uses your webpage title, URL and meta description in order to display
                            relevant summarized information about the page. If these elements are too long,
                            Google will truncate their content.
                            Learn more on how snippets are created in <a href="https://developers.google.com/search/docs/advanced/appearance/good-titles-snippets#how-snippets-are-created">this Google guide</a>.
                        </p>
                    </div>
                </li>
            </ul>

            <div class="px-4 py-2">
                <div class="uk-grid">
                    <div class="uk-width-1-1">
                        <div class="bg-grey p-3 border uk-border-rounded">
                            <p class="mb-0">
                                {{ str_replace('/',' ',preg_replace( "#^[^:/.]*[:/]+#i", "", $summaryTask['url'])) }}
                            </p>
                            <h5 class="uk-text-light py-2 mb-0 text-blue">
                                {{ $summaryTask['meta']['title'] }}
                            </h5>
                            <p class="mb-0">{{ $summaryTask['meta']['description'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-bottom py-4">
            <ul uk-accordion class="content-accr p-0 m-0">
                <li>
                    <div class="uk-accordion-title px-4 py-2">
                        <div class="uk-grid">
                            <div class="uk-width-1-4">
                                <h5 class="uk-text-light">H1 check</h5>
                            </div>
                            <div class="uk-width-3-4">{{ @$summaryTask['meta']['htags']['h1'][0] }}</div>
                        </div>
                    </div>
                    <div class="uk-accordion-content p-4 mt-0">
                        <p>Check if any H1 headings are used in your page. H1 headings are HTML tags that
                            are
                            not visible to users, but can help clarify the overall theme or purpose of your
                            page
                            to search engines.<br>

                            The H1 tag represents the most important heading on your page, e.g., the title
                            of
                            the page or blog post. All other topics and categories on that page would likely
                            line up below that main header as a subhead, typically going more in-depth about
                            a
                            topic within that main header.<br>

                            To determine if you’re putting your H1 tag to good use, follow these advices:
                            <ol>
                                <li>Your website should have only one H1 tag. If you have more than one H1 tag
                                    on a
                                page, change the other H1 tags to an H2 or H3;</li>
                                <li>Your H1 tag should be at the top of the page content (above any other
                                    heading
                                    tags in the page code). If your site is divided in to columns the left
                                    column
                                may appear “higher” in the code.</li>
                            </ol>
                            <a href="https://sitechecker.pro/h1-tag/">Use this guide</a> to learn more on how to
                            use
                            h1 tag properly.
                        </p>
                    </div>
                </li>
            </ul>

            <div class="px-4 py-2">
                <div class="uk-grid">
                    
                    @if(isset($summaryTask['meta']['htags']['h1']) && count($summaryTask['meta']['htags']['h1']) == 1)
                        <div class="uk-width-1-4"><img src=" {{ URL::asset('public/vendor/internal-pages/images/check-icon.png') }}" class="sub-2 mr-1" alt="" /> H1 count</div>
                    @else
                        <div class="uk-width-1-4"><img src="{{ URL::asset('public/vendor/internal-pages/images/cross-icon.png') }}"  class="sub-2 mr-1" alt="" /> H1 count</div>
                    @endif
                        <div class="uk-width-3-4">{{ isset($summaryTask['meta']['htags']['h1']) ? count($summaryTask['meta']['htags']['h1']) :"0" }} tags (Recommended: 1 H1 tag)</div>
                </div>
            </div>

            <div class="px-4 py-2">
                <div class="uk-grid">
                    @if(isset($summaryTask['meta']['htags']['h1']) && strlen($summaryTask['meta']['htags']['h1'][0]) > 5 &&
                    strlen($summaryTask['meta']['htags']['h1'][0]) < 70) <div class="uk-width-1-4">
                        <img src="{{ URL::asset('public/vendor/internal-pages/images/check-icon.png') }}"
                        class="sub-2 mr-1" alt="" /> H1 length
                    </div>
                    @else
                    <div class="uk-width-1-4">
                        <img src="{{ URL::asset('public/vendor/internal-pages/images/cross-icon.png') }}" class="sub-2 mr-1"
                        alt="" /> H1 length
                    </div>
                    @endif
                    <div class="uk-width-3-4">{{ isset($summaryTask['meta']['htags']['h1']) ? strlen($summaryTask['meta']['htags']['h1'][0]) : "0" }} characters
                    (Recommended: 5-70 characters)</div>
                </div>
            </div>

            <div class="px-4 py-2">
                <div class="uk-grid">
                    <div class="uk-width-1-4"><img src="{{ URL::asset('public/vendor/internal-pages/images/check-icon.png') }}" class="sub-2 mr-1" alt="" /> H1 = Title</div>
                    <div class="uk-width-3-4">H1 does not match Title</div>
                </div>
            </div>
        </div>

        <div class="border-bottom py-4 htags">
            <h5 class="uk-text-light px-4 mb-0 py-1">H1-H6 structure</h5>
            <div class="px-4 py-2">
                <table class="uk-table mb-0">
                    <thead>
                        <tr class="border bg-grey uk-border-rounded">
                            <th class="uk-text-center"> &#60;H1&#62;</th>
                            <th class="uk-text-center"> &#60;H2&#62;</th>
                            <th class="uk-text-center"> &#60;H3&#62;</th>
                            <th class="uk-text-center"> &#60;H4&#62;</th>
                            <th class="uk-text-center"> &#60;H5&#62;</th>
                            <th class="uk-text-center"> &#60;H6&#62;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="uk-border-rounded">
                            <td class="uk-text-center">
                                {{ isset($summaryTask['meta']['htags']['h1']) ? count($summaryTask['meta']['htags']['h1']) : 0 }}
                            </td>
                            <td class="uk-text-center">
                                {{ isset($summaryTask['meta']['htags']['h2']) ? count($summaryTask['meta']['htags']['h2']) : 0 }}
                            </td>
                            <td class="uk-text-center">
                                {{ isset($summaryTask['meta']['htags']['h3']) ? count($summaryTask['meta']['htags']['h3']) : 0 }}
                            </td>
                            <td class="uk-text-center">
                                {{ isset($summaryTask['meta']['htags']['h4']) ? count($summaryTask['meta']['htags']['h4']) : 0 }}
                            </td>
                            <td class="uk-text-center">
                                {{ isset($summaryTask['meta']['htags']['h5']) ? count($summaryTask['meta']['htags']['h5']) : 0 }}
                            </td>
                            <td class="uk-text-center">
                                {{ isset($summaryTask['meta']['htags']['h6']) ? count($summaryTask['meta']['htags']['h6']) : 0 }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-2">
                <ul class="all-pages-con">
                    <?php $htagCounter =  1; ?>
                    @foreach($summaryTask['meta']['htags'] as $htagKey => $htagValue)
                    @foreach($htagValue as $key => $value)
                    <li> <span>&#60;{{ $htagKey }}&#62;</span> <span class="no">{{ $value }}</span> </li>

                    @if($htagCounter == 10)
                </ul>
                <ul uk-accordion="" class="uk-accordion">
                    <li class="">
                        <div class="uk-accordion-content" hidden="">
                            <ul class="all-pages-con">
                                @endif
                                <?php $htagCounter++; ?>
                                @endforeach
                                @endforeach
                                @if($htagCounter < 10) 
                            </ul>
                                @else
                            <ul> 
                            </ul>
                        </div>
                        <a class="uk-accordion-title" href="javascript:;"> <span uk-icon="icon:triangle-down" class="uk-icon text-secondary"></span> Show All</a>
                    </li>
                </ul>
                    @endif
            </div>
        </div>

        <div class="border-bottom py-4">
            <h5 class="uk-text-light px-4 mb-0 py-1 ">Content check</h5>
            <ul uk-accordion class="content-accr p-0 m-0 ">
                <li>
                    <div class="uk-accordion-title px-4 py-2 ">
                        <div class="uk-grid ">
                            <div class="uk-width-1-4"><img src="{{ URL::asset('public/vendor/internal-pages/images/check-icon.png') }}" class="sub-2 mr-1 " alt=" " /> Text Length</div>
                            <div class="uk-width-3-4">
                                {{ $summaryTask['meta']['content']['plain_text_word_count'] }}
                            characters (Recommended: more than 500 words)</div>
                        </div>
                    </div>
                    <div class="uk-accordion-content p-4 mt-0 ">
                        <p>This is a number of characters in page's text. You have a higher chance of ranking in Google if
                            you write long, high-quality blog posts.

                            When your text is longer, Google has more clues to determine what it is about. The longer your
                            (optimized) text, the more often your keywords appears.

                            Also, if a page consists of few words, Google is more likely to think of it as thin content. All
                            search engines want to provide the best answers to the queries people have. Thin content is less
                            likely to offer a complete answer and satisfy the needs of the public. Consequently, it will
                        probably not rank very high.</p>
                    </div>
                </li>
                <li>
                    <div class="uk-accordion-title px-4 py-2 ">
                        <div class="uk-grid ">
                            <div class="uk-width-1-4 "><img src="{{ URL::asset('public/vendor/internal-pages/images/check-icon.png') }} " class="sub-2 mr-1 " alt=" " /> Readability index </div>
                            <div class="uk-width-3-4 ">
                                {{ round($summaryTask['meta']['content']['automated_readability_index'],'2') }}
                            (Recommended: more than 10)</div>
                        </div>
                    </div>
                    <div class="uk-accordion-content p-4 mt-0 ">
                        <p>The text-to-code ratio describes the relationship between text content and the underlying source
                            code of a website. Each website consists of source code which initially structures the contents
                            of the website in the background as well as the text that is readable by the users.

                            Google doesn't use the text to HTML ratio as a ranking signal, but it may be seen as a sign that
                            a webpage has bloated HTML, which when served to users, especially on mobile, will cause "slow
                            everything down". Low amount of unique content in relation to HTML code on the page also may be
                        an indication that the page provides information of little value to the reader.</p>
                    </div>
                </li>
                </ul>
        </div>
    </div>
</div>

<div class="audit-white-box mb-40 pa-0" id="structure_data">
    <div class="audit-box-head">
        <h2><img src="{{ URL::asset('public/vendor/internal-pages/images/structure-data-icon.png') }}" alt="">
        Structure Data</h2>
    </div>
    <div class="audit-box-body">
        <div class="py-4">
            <h5 class="uk-text-light px-4 mb-0 py-1">Open Graph</h5>
            @if(isset($summaryTask['meta']['social_media_tags']))
            @foreach($summaryTask['meta']['social_media_tags'] as $key => $value)
            <div class="px-4 py-2">
                <div class="uk-grid">
                    <div class="uk-width-1-4"><img src="{{ URL::asset('public/vendor/internal-pages/images/check-icon.png') }}" class="sub-2 mr-1" alt="" /> {{ $key }}</div>
                    <div class="uk-width-3-4">{{ $value }}</div>
                </div>
            </div>
            @endforeach
            @else
            <div class="px-4 py-2">
                <div class="uk-grid">
                    Missing social media Open Graph
                </div>
            </div>
            @endif
        </div>
    </div>
</div>