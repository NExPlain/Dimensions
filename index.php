<?php
/**
 * The initial page of Dimensions.
 *
 * @author Renfei Song
 */

require_once "functions.php";
get_header("Dimensions");
?>

<style>
    body {
        font-family: Monospace;
        background-color: #f0f0f0;
        margin: 0px;
        overflow: hidden;
    }

    #circles {
        -webkit-filter: blur(10px);
        -moz-filter: blur(10px);
        -o-filter: blur(10px);
        -ms-filter: blur(10px);
        filter: blur(10px);
    }

    #no-blur {
        font-size:100px;
        -webkit-filter: blur(0px); !important
        -moz-filter: blur(0px); !important
        -o-filter: blur(0px); !important
        -ms-filter: blur(0px); !important
        filter: blur(0px); !important
    }
</style>
</head>

<body>

    <div id="circles">

        <div id="no-blur">
            <p>no blur</p>
        </div>

    </div>
		<script src="three.min.js"></script>
		<script src="stats.min.js"></script>
        <script src="animation.js"></script>

<?php
get_footer();
?>
