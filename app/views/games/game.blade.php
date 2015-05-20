@extends('layouts.wrapper')

@section('title')
<title>Twitch Random | {{{ $game }}}</title>
@stop

@section('css')
<link rel="stylesheet" href="/css/redesign/main.min.css">
@stop

@section('js')
<script>
    @include("layouts.js.loading")

    function loadGallery(galleryURL, galleryID){
        $.ajax({
            url: galleryURL
        }).done(function(data){
            $(galleryID+" .loading").hide();
            $(galleryID).append(data);
            $(galleryID+" .gallery-cont").niceScroll({cursorcolor:"#6441A5",cursoropacitymin:1,cursorwidth: "10px"})
            $(galleryID+" .gallery-reload").click(function(){
                $(galleryID+" .loading").show();
                $(galleryID+" .gallery-holder").remove();
                loadGallery(galleryURL, galleryID);
            });
        }).fail(function(data){
            console.log(data);
        });
    }

    $(document).ready(function(){
        $("html").niceScroll({cursorcolor:"#6441A5"});
        $.ajax({
            url: "/ajax/game/{{{ rawurlencode($game) }}}/1"
        }).done(function(data){
            $(".jumbotron").append(data);
        }).fail(function(data){
            console.log(data);
            $(".jumbotron>.loading>.text").addClass("error").text("Error: "+data.responseJSON.error.message);
        });

        loadGallery("/ajax/game/{{{ rawurlencode($game) }}}/9", "#random-game-gallery");
        loadGallery("/ajax/top/{{{ rawurlencode($game) }}}", "#top-game-gallery");
        loadGallery("/ajax/featured/3", "#gallery-featured");

        $(".gallery-control-left").click(function(){
            if(!$(this).hasClass("disabled")){
                var gallery = $(this).siblings(".gallery-holder").find(".gallery-cont");
                var left = gallery.scrollLeft();
                var width = gallery.width() + 15;
                gallery.getNiceScroll(0).doScrollLeft(left - width,400);
            }
        });
        $(".gallery-control-right").click(function(){
            if(!$(this).hasClass("disabled")){
                var gallery = $(this).siblings(".gallery-holder").find(".gallery-cont");
                var left = gallery.scrollLeft();
                var width = gallery.width() + 15;
                gallery.getNiceScroll(0).doScrollLeft(left + width,400);
            }
        });

        $(".jumbocontainer").on("click", "#randomize-stream", function(e){
            e.preventDefault();
            $("#main-stream-container").remove();
            $.ajax({
                url: "/ajax/game/{{{ rawurlencode($game) }}}/1"
            }).done(function(data){
                $(".jumbotron").append(data);
            }).fail(function(data){
                console.log(data);
            });
        });

        setInterval(function(){ $(".loading:visible>.text").setRandomText(); }, 1600);
    });
</script>
@stop

@section('content')

@include("layouts.header")
<div class="jumbocontainer">
    @if(Config::get('app.showStream'))  {{-- Don't show ads for dev pages --}}
    <div class="ad horizontal">
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <!-- TwitchRandom Responsive Ad - Horizontal -->
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-1737596577801120"
             data-ad-slot="6490371140"
             data-ad-format="auto"></ins>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    </div>
    @endif
    <div class="container med-container stream-container">
        <div class="jumbotron">

        </div>
    </div>
</div>
<div class="gallery-container lg-container">
    @if(Config::get('app.showStream'))  {{-- Don't show ads for dev pages --}}
    <div class="ad horizontal">
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <!-- TwitchRandom Responsive Ad - Horizontal -->
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-1737596577801120"
             data-ad-slot="6490371140"
             data-ad-format="auto"></ins>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    </div>
    @endif
    <div class="row">
        <div class="gallery featured col-sm-9" id="gallery-featured">
            <div class="gallery-title">
                <span class="title">Featured Streams</span>
            </div>
            <div class="loading" id="featured-gallery-loading">
                <img src="/img/loading.gif" alt="loading">
                <span class="text">Loading Gallery...</span>
            </div>
        </div>
        <div class="col-sm-3 ad block">
            @if(Config::get('app.showStream'))  {{-- Don't show ads for dev pages --}}
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- TwitchRandom Responsive Ad - Vertical -->
            <ins class="adsbygoogle"
                 style="display:block"
                 data-ad-client="ca-pub-1737596577801120"
                 data-ad-slot="6130444348"
                 data-ad-format="auto"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="gallery col-sm-12" id="random-game-gallery">
            <div class="gallery-title">
                <span class="title">{{{ $game }}} | Random Streams</span>
            </div>
            <div class="gallery-control gallery-control-left"><span class="glyphicon glyphicon-chevron-left"></span></div>
            <div class="gallery-control gallery-control-right"><span class="glyphicon glyphicon-chevron-right"></span></div>
            <div class="loading" id="random-gallery-loading">
                <img src="/img/loading.gif" alt="loading">
                <span class="text">Loading Gallery...</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="gallery col-sm-12" id="top-game-gallery">
            <div class="gallery-title">
                <span class="title">{{{ $game }}} | Top Streams</span>
            </div>
            <div class="gallery-control gallery-control-left"><span class="glyphicon glyphicon-chevron-left"></span></div>
            <div class="gallery-control gallery-control-right"><span class="glyphicon glyphicon-chevron-right"></span></div>
            <div class="loading" id="top-gallery-loading">
                <img src="/img/loading.gif" alt="loading">
                <span class="text">Loading Gallery...</span>
            </div>
        </div>
    </div>
</div>

@include("layouts.footer")
@stop