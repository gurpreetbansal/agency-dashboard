@extends('layouts.outer_layout')
@section('content')
<div class="pricing-wrapper clearfix">
    <!-- Titulo -->
    <h1 class="pricing-table-title"></h1>
    <input type="hidden" class="plan_status">
    <form name="price" method="post" action="{{ url('/register') }}"> 
        <input type="hidden" name="user_id" value="{{@Auth::user()->id}}" />
        @csrf
        <?php
        if (isset($packages) && !empty($packages)) {
            foreach ($packages as $key => $value) {
                ?>

                <div class="pricing-table">
                    <h3 class="pricing-title">{{$value->name}}</h3>
                    <div class="price">${{$value->amount}}</div>
                    <!-- Lista de Caracteristicas / Propiedades -->
                    <ul class="table-list">
                        <?php
                        if ($value->number_of_projects > 0) {
                            ?>
                            <li>{{$value->number_of_projects}} <span>Projects</span></li>
                        <?php } ?>
                        <?php
                        if ($value->number_of_keywords > 0) {
                            ?>
                            <li>{{$value->number_of_keywords}} <span>Keyword(s)</span></li>
                        <?php } ?>
                        <?php
                        if ($value->free_trial == 1) {
                            ?>
                            <li><span>Free Trial for </span>{{$value->duration}} <span>Days</span></li>

                        <?php } ?>
                    </ul>
                    <!-- Contratar / Comprar -->
                    <div class="table-buy">

                        <p>${{$value->amount}}</p>
                        <button type="submit" data-amount="{{$value->id}}" class="pricing-action">Buy Now</button>
                    </div>
                </div>
                <?php
            }
        }
        ?>
        <input type="hidden" id="price" name="package_id" value=""/>
    </form>
</div>

<!-- #end content-container -->

</div> <!-- #end main-container -->
@endsection