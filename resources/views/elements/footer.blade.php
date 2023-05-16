<div id="loading">
    <img src="{{url('img/loading.gif')}}" alt="loading">
</div>

<button id="startConfetti" class="hidden">Start animation</button>
<button id="stopConfetti" class="hidden">Stop animation</button>

<footer>
    <p class="">@ ban.chan.tran - 2023</p>
</footer>

<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="{{url('js/jquery.confetti.js')}}"></script>
<script src="{{url('js/jquery.mark.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
<script src="{{url('js/common.js?v=' . time())}}"></script>
<!--[if lt IE 9]>
<script src="{{url('js/html5shiv-printshiv.js')}}"></script>
<script src="{{url('js/selectivizr.js')}}"></script>
<![endif]-->
