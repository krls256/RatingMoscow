<?php
if (!isset($_SERVER['HTTP_USER_AGENT']) || stripos($_SERVER['HTTP_USER_AGENT'], 'Chrome-Lighthouse') === false): ?>

	<script>$(window).on("load",function(){var e=document.createElement("script");e.src="https://www.google.com/recaptcha/api.js",$(".wrapper").after(e)});</script>
	<script src="/js/jquery.mask.min.js?v=0.0.1"></script>
	<script src="/js/formstyler.js?v=0.0.1"></script>
	
	 <?php if ( $set['ya_metriks'] ): ?>
		<!-- Yandex.Metrika counter -->
			<script type="text/javascript" >!function(e,a,t,n,c){e.ym=e.ym||function(){(e.ym.a=e.ym.a||[]).push(arguments)},e.ym.l=+new Date,n=a.createElement(t),c=a.getElementsByTagName(t)[0],n.async=1,n.src="https://mc.yandex.ru/metrika/tag.js",c.parentNode.insertBefore(n,c)}(window,document,"script"),ym(<?=$set['ya_metriks']?>,"init",{clickmap:!0,trackLinks:!0,accurateTrackBounce:!0,webvisor:!0});</script>
			<noscript><div><img src="https://mc.yandex.ru/watch/<?=$set['ya_metriks']?>" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
		<!-- End yandex.Metrika counter -->
	<?php endif; ?>
<?php endif; ?>
<?php if ( $set['google_metriks'] ): ?>
	<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=<?=$set['google_metriks']?>"></script>
		<script>function gtag(){dataLayer.push(arguments)}window.dataLayer=window.dataLayer||[],gtag("js",new Date),gtag("config","<?=$set['google_metriks']?>");</script>
	<!-- End global site tag (gtag.js) - Google Analytics -->
<?php endif; ?>

<script src="/js/main.js??v=0.0.1"></script>

<!-- RoiStat -->
<script>
(function(w, d, s, h, id) {
    w.roistatProjectId = id; w.roistatHost = h;
    var p = d.location.protocol == "https:" ? "https://" : "http://";
    var u = /^.*roistat_visit=[^;]+(.*)?$/.test(d.cookie) ? "/dist/module.js" : "/api/site/1.0/"+id+"/init";
    var js = d.createElement(s); js.charset="UTF-8"; js.async = 1; js.src = p+h+u; var js2 = d.getElementsByTagName(s)[0]; js2.parentNode.insertBefore(js, js2);
})(window, document, 'script', 'cloud.roistat.com', '8ed8c9333a1a1c819bc7646765996e88');
</script>