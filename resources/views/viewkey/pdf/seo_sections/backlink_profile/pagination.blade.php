<div class="project-entries">
  <p>Showing {{$backlink_records->firstItem() }} to {{ $backlink_records->lastItem() }} of {{ $backlink_records->total() }} entries</p>
</div>
<div class="pagination Backlinks">

  @if($backlink_records->lastPage() >1 )
  <ul>
    <li class="prev {{ ($backlink_records->currentPage() == 1) ? ' disabled' : '' }}">
      <a href="{{$backlink_records->url(1)}}">Previous</a>
    </li>
    @for ($i = 1; $i <= $backlink_records->lastPage(); $i++)
    <li class="{{ ($backlink_records->currentPage() == $i) ? ' active' : '' }}">
      <a href="{{ $backlink_records->url($i) }}">{{ $i }}</a>
    </li>
    @endfor

    <li class="active next {{ ($backlink_records->currentPage() == $backlink_records->lastPage()) ? ' disabled' : '' }}">
      <a href="{{$backlink_records->url($backlink_records->currentPage()+1)}}">Next</a>
    </li>
  </ul>

  @endif
</div>