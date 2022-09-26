
	<a href="#Overview">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12,8h5.33a1.34,1.34,0,1,0,0-2.67H12A1.34,1.34,0,0,0,12,8Zm0,5.33h5.33a1.33,1.33,0,1,0,0-2.66H12a1.33,1.33,0,0,0,0,2.66Zm0,5.34h5.33a1.34,1.34,0,1,0,0-2.67H12a1.34,1.34,0,0,0,0,2.67ZM5.33,5.33H8V8H5.33Zm0,5.34H8v2.66H5.33Zm0,5.33H8v2.67H5.33Z"></path><path d="M21.33,0H2.67A2.68,2.68,0,0,0,0,2.67V21.33A2.68,2.68,0,0,0,2.67,24H21.33A2.68,2.68,0,0,0,24,21.33V2.67A2.68,2.68,0,0,0,21.33,0Zm0,21.33H2.67V2.67H21.33Z"></path></svg>
		<span>Overview</span>
	</a>
	<a href="#SEO">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 17.49 17.49"><path d="M12.5,11h-.79l-.28-.27a6.51,6.51,0,1,0-.7.7l.27.28v.79l5,5L17.49,16Zm-6,0A4.5,4.5,0,1,1,11,6.5,4.49,4.49,0,0,1,6.5,11Z"></path></svg>
		<span>SEO</span>
		@if($summaryAuditPages->highIssuesSeoCount)
		<span class="ibadge badge-danger">{{ number_format($summaryAuditPages->highIssuesSeoCount, 0, __('.'), __(',')) }}</span>
		@endif
        @if($summaryAuditPages->mediumIssuesSeoCount)
		<span class="ibadge badge-warning">{{ number_format($summaryAuditPages->mediumIssuesSeoCount, 0, __('.'), __(',')) }}</span>
		@endif
        @if($summaryAuditPages->lowIssuesSeoCount)
        <span class="ibadge badge-secondary">{{ number_format($summaryAuditPages->lowIssuesSeoCount, 0, __('.'), __(',')) }}</span>
        @endif

	</a>
	<a href="#Performance">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19.97 15.96"><path d="M18.35,4.53,17.12,6.38A8,8,0,0,1,16.9,14H3A8,8,0,0,1,13.55,2.81L15.4,1.58A10,10,0,0,0,1.32,15,2,2,0,0,0,3,16H16.89a2,2,0,0,0,1.74-1,10,10,0,0,0-.27-10.44Z"></path><path d="M8.56,11.37a2,2,0,0,0,2.83,0h0l5.66-8.49L8.56,8.54a2,2,0,0,0,0,2.83Z"></path></svg>
		<span>Performance</span>
		@if($summaryAuditPages->highIssuesPerformanceCount)
		<span class="ibadge badge-danger">{{ number_format($summaryAuditPages->highIssuesPerformanceCount, 0, __('.'), __(',')) }}</span>
		@endif
        @if($summaryAuditPages->mediumIssuesPerformanceCount)
		<span class="ibadge badge-warning">{{ number_format($summaryAuditPages->mediumIssuesPerformanceCount, 0, __('.'), __(',')) }}</span>
		@endif
        @if($summaryAuditPages->lowIssuesPerformanceCount)
        <span class="ibadge badge-secondary">{{ number_format($summaryAuditPages->lowIssuesPerformanceCount, 0, __('.'), __(',')) }}</span>
        @endif
		
	</a>
	<a href="#Security">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 20"><path d="M6.5,11H4V8H6.5V5.5h3V8H12v3H9.5v2.5h-3ZM8,0,0,3V9.09C0,14.14,3.41,18.85,8,20c4.59-1.15,8-5.86,8-10.91V3Zm6,9.09a9.34,9.34,0,0,1-6,8.83A9.33,9.33,0,0,1,2,9.09V4.39L8,2.14l6,2.25Z"></path></svg>
		<span>Security</span>

		@if($summaryAuditPages->highIssuesSecurityCount)
		<span class="ibadge badge-danger">{{ number_format($summaryAuditPages->highIssuesSecurityCount, 0, __('.'), __(',')) }}</span>
		@endif
        @if($summaryAuditPages->mediumIssuesSecurityCount)
		<span class="ibadge badge-warning">{{ number_format($summaryAuditPages->mediumIssuesSecurityCount, 0, __('.'), __(',')) }}</span>
		@endif
        @if($summaryAuditPages->lowIssuesSecurityCount)
        <span class="ibadge badge-secondary">{{ number_format($summaryAuditPages->lowIssuesSecurityCount, 0, __('.'), __(',')) }}</span>
        @endif
	</a>
	<a href="#Miscellaneous">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19 19.08"><path d="M13.56,6.56,9.85.48a1,1,0,0,0-1.7,0L4.43,6.56a1,1,0,0,0,.85,1.52h7.43A1,1,0,0,0,13.56,6.56Zm-6.5-.48L9,2.92l1.93,3.16Z"></path><path d="M19,14.58a4.5,4.5,0,1,0-4.5,4.5A4.49,4.49,0,0,0,19,14.58Zm-4.5,2.5a2.5,2.5,0,1,1,2.5-2.5A2.5,2.5,0,0,1,14.5,17.08Z"></path><path d="M7,10.58H1a1,1,0,0,0-1,1v6a1,1,0,0,0,1,1H7a1,1,0,0,0,1-1v-6A1,1,0,0,0,7,10.58Zm-1,6H2v-4H6Z"></path></svg>
		<span>Miscellaneous</span>
		@if($summaryAuditPages->highIssuesMiscellaneousCount)
		<span class="ibadge badge-danger">{{ number_format($summaryAuditPages->highIssuesMiscellaneousCount, 0, __('.'), __(',')) }}</span>
		@endif
        @if($summaryAuditPages->mediumIssuesMiscellaneousCount)
		<span class="ibadge badge-warning">{{ number_format($summaryAuditPages->mediumIssuesMiscellaneousCount, 0, __('.'), __(',')) }}</span>
		@endif
        @if($summaryAuditPages->lowIssuesMiscellaneousCount)
        <span class="ibadge badge-secondary">{{ number_format($summaryAuditPages->lowIssuesMiscellaneousCount, 0, __('.'), __(',')) }}</span>
        @endif
	</a>
