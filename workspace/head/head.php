
<style type="text/css">
        @import "../dojodev/dijit/themes/soria/soria.css";
    </style>
	<style type="text/css">
            html, body, #main {
                /* make the body expand to fill the visible window */
                width: 100%; 
                height: 100%;
            }
            
            #main{
            	width: 960px;
            	margin: 0 auto;
            }

            h1, h2, h3 { margin: 0.5em 0 1em 0}
            p   { margin: 0 0 1em 0}

            .box     { border: 1px #bbb solid;}
            .content { padding: 0.5em; overflow: auto}

            #header   { width: 960px; margin: 0 auto 15px auto; }
            #sidebar  { width: 150px;}
            #content  { padding: 1em;}
            #footer   { width: 960px;height: 50px; margin: 10px auto;}

            #sidebar ul { margin-left: -1em;}

            #mainstack { width: 75%; height: 75%; border: 1px #888 solid}

			.dijitTabContent{font-size: .75em;}
        </style>
		<script type="text/javascript">
            djConfig = {
                isDebug:      true,
                parseOnLoad:   true
            };
        </script>

       <!-- <script type="text/javascript" src="../dojodev/dojo/dojo.js"></script>-->
<script src="//ajax.googleapis.com/ajax/libs/dojo/1.7.2/dojo/dojo.js"></script>
        <script type="text/javascript">
            dojo.require("dojo.parser");
			dojo.require("dijit.layout.ContentPane");
            dojo.require("dijit.layout.BorderContainer");
            dojo.require("dijit.layout.StackContainer");
            dojo.require("dijit.layout.AccordionContainer");
            dojo.require("dijit.layout.SplitContainer");
            dojo.require("dijit.layout.TabContainer");
            dojo.require("dijit.layout.LinkPane");
        </script>
		<meta name="robots" content="nofollow" />