    <?php
$style=" #sidebar span.collapsCat {
        border:0;
        padding:0; 
        margin:0; 
        cursor:pointer;
}
#sidebar li.collapsCat a.self {font-weight:bold}
#sidebar ul.collapsCatList ul.collapsCatList:before {content:'';} 
#sidebar ul.collapsCatList li.collapsCat:before {content:'';} 
#sidebar ul.collapsCatList li.collapsCat {list-style-type:none}
#sidebar ul.collapsCatList li {
       margin:0 0 0 1.2em;
       text-indent:-1em}
#sidebar ul.collapsCatList li.collapsCatPost:before {content: '\\\\00BB \\\\00A0' !important;} 
#sidebar ul.collapsCatList li.collapsCat .sym {
   font-size:1.2em;
   font-family:Monaco, 'Andale Mono', 'FreeMono', 'Courier new', 'Courier', monospace;
    padding-right:5px;}";

$default=$style;

$block=" #sidebar li.collapsCat a {
            display:inline-block;
            text-decoration:none;
            margin:0;
            padding:0;
            }
#sidebar li.collapsCat ul li.collapsCatPost a {
            display:block;
}
#sidebar li.collapsCat a:hover {
            background:#CCC;
            text-decoration:none;
          }
#sidebar span.collapsCat {
        border:0;
        padding:0; 
        margin:0; 
        cursor:pointer;
}
#sidebar li.collapsCat a.self {font-weight:bold}
#sidebar ul.collapsCatList ul.collapsCatList:before {content:'';} 
#sidebar ul.collapsCatList li.collapsCat:before {content:'';} 
#sidebar ul.collapsCatList li.collapsCat {list-style-type:none}
#sidebar ul.collapsCatList li.collapsCatPost {
      }
#sidebar ul.collapsCatList li.collapsCat .sym {
   font-size:1.2em;
   font-family:Monaco, 'Andale Mono', 'FreeMono', 'Courier new', 'Courier', monospace;
    float:left;
    padding-right:5px;
}
";

$noArrows=" #sidebar span.collapsCat {
        border:0;
        padding:0; 
        margin:0; 
        cursor:pointer;
}
#sidebar li.collapsCat a.self {font-weight:bold}
#sidebar ul.collapsCatList ul.collapsCatList:before {content:'';} 
#sidebar ul.collapsCatList li.collapsCat:before {content:'';} 
#sidebar ul.collapsCatList li.collapsCat {list-style-type:none}
#sidebar ul.collapsCatList li.collapsCat .sym {
   font-size:1.2em;
   font-family:Monaco, 'Andale Mono', 'FreeMono', 'Courier new', 'Courier', monospace;
    padding-right:5px;}";
$selected='default';
$custom=get_option('collapsCatStyle');
?>
