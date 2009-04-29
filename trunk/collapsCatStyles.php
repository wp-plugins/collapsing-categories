    <?php
$style="span.collapsCat {
        border:0;
        padding:0; 
        margin:0; 
        cursor:pointer;
}
li.collapsCat a.self {font-weight:bold}
ul.collapsCatList ul.collapsCatList:before {content:'';} 
ul.collapsCatList li.collapsCat:before {content:'';} 
ul.collapsCatList li.collapsCat {list-style-type:none}
ul.collapsCatList li.collapsCatPost {
       margin:0 0 0 2em;}
ul.collapsCatList li.collapsCatPost:before {content: '\\\\00BB \\\\00A0' !important;} 
ul.collapsCatList li.collapsCat .sym {
   font-size:1.2em;
   font-family:Monaco, 'Andale Mono', 'FreeMono', 'Courier new', 'Courier', monospace;
    padding-right:5px;}";

$default=$style;

$block="li.collapsCat a {
            display:inline-block;
            text-decoration:none;
            margin:0;
            padding:0;
            }
li.collapsCat ul li.collapsCatPost a {
            display:block;
}
li.collapsCat a:hover {
            background:#CCC;
            text-decoration:none;
          }
span.collapsCat {
        border:0;
        padding:0; 
        margin:0; 
        cursor:pointer;
}
li.collapsCat a.self {font-weight:bold}
ul.collapsCatList ul.collapsCatList:before {content:'';} 
ul.collapsCatList li.collapsCat:before {content:'';} 
ul.collapsCatList li.collapsCat {list-style-type:none}
ul.collapsCatList li.collapsCatPost {
      }
ul.collapsCatList li.collapsCat .sym {
   font-size:1.2em;
   font-family:Monaco, 'Andale Mono', 'FreeMono', 'Courier new', 'Courier', monospace;
    float:left;
    padding-right:5px;
}
";

$noArrows="span.collapsCat {
        border:0;
        padding:0; 
        margin:0; 
        cursor:pointer;
}
li.collapsCat a.self {font-weight:bold}
ul.collapsCatList ul.collapsCatList:before {content:'';} 
ul.collapsCatList li.collapsCat:before {content:'';} 
ul.collapsCatList li.collapsCat {list-style-type:none}
ul.collapsCatList li.collapsCat .sym {
   font-size:1.2em;
   font-family:Monaco, 'Andale Mono', 'FreeMono', 'Courier new', 'Courier', monospace;
    padding-right:5px;}";
$selected='default';
$custom=get_option('collapsCatStyle');
?>
