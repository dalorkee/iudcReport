<!-- Bootstrap 3.3.7 -->
{{ Html::style(('public/AdminLTE-2.4.2/bower_components/bootstrap/dist/css/bootstrap.min.css')) }}
<!-- Font Awesome -->
{{ Html::style(('public/AdminLTE-2.4.2/bower_components/font-awesome/css/font-awesome.min.css')) }}
<!-- Ionicons -->
{{ Html::style(('public/AdminLTE-2.4.2/bower_components/Ionicons/css/ionicons.min.css')) }}
<!-- Theme style -->
{{ Html::style(('public/AdminLTE-2.4.2/dist/css/AdminLTE.min.css')) }}
<!-- AdminLTE Skins. Choose a skin from the css/skins
	folder instead of downloading all of them to reduce the load. -->
{{ Html::style(('public/AdminLTE-2.4.2/dist/css/skins/_all-skins.min.css')) }}
<style>
    .treegrid-indent {
        width: 0px;
        height: 16px;
        display: inline-block;
        position: relative;
    }

    .treegrid-expander {
        width: 0px;
        height: 16px;
        display: inline-block;
        position: relative;
        left:-17px;
        cursor: pointer;
    }
</style>
