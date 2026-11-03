@php
    $clarityId = env('CLARITY_PROJECT_ID', 'z67iifc6k5');
@endphp

@if($clarityId)
    <!-- Microsoft Clarity Tracker (Deferred loading to prioritize critical render & LCP) -->
    <script type="text/javascript">
        function initClarity() {
            if (window._clarityInitialized) return;
            window._clarityInitialized = true;
            (function(c,l,a,r,i,t,y){
                c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
                t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
                y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
            })(window, document, "clarity", "script", "{{ $clarityId }}");
        }

        if (document.readyState === 'complete') {
            setTimeout(initClarity, 1500);
        } else {
            window.addEventListener('load', function() {
                setTimeout(initClarity, 1500);
            });
        }
    </script>
@endif

