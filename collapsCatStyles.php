    <?php
$style="#sidebar span.collapsing.categories.post {
        border:0;
        padding:0; 
        margin:0; 
        cursor:pointer;
}
#sidebar li.collapsing.categories.post a.self {font-weight:bold}
#sidebar ul.collapsing.categories.list ul.collapsing.categories.list:before {content:'';} 
#sidebar ul.collapsing.categories.list li.collapsing.categories.post:before {content:'';} 
#sidebar ul.collapsing.categories.list li.collapsing.categories.post {list-style-type:none}
#sidebar ul.collapsing.categories.list li.collapsing.categories.post {
       margin:0 0 0 2em;}
#sidebar ul.collapsing.categories.list li.collapsing.categories.post:before {content: '\\\\00BB \\\\00A0' !important;} 
#sidebar ul.collapsing.categories.list li.collapsing.categories.post .sym {
   font-size:1.2em;
   font-family:Monaco, 'Andale Mono', 'FreeMono', 'Courier new', 'Courier', monospace;
    padding-right:5px;}";

$default=$style;

$block="#sidebar li.collapsing.categories.post a {
            display:inline-block;
            text-decoration:none;
            margin:0;
            padding:0;
            }
#sidebar li.collapsing.categories.post ul li.collapsing.categories.post a {
            display:block;
}
#sidebar li.collapsing.categories.post a:hover {
            background:#CCC;
            text-decoration:none;
          }
#sidebar span.collapsing.categories.post {
        border:0;
        padding:0; 
        margin:0; 
        cursor:pointer;
}
#sidebar li.collapsing.categories.post a.self {font-weight:bold}
#sidebar ul.collapsing.categories.list ul.collapsing.categories.list:before {content:'';} 
#sidebar ul.collapsing.categories.list li.collapsing.categories.post:before {content:'';} 
#sidebar ul.collapsing.categories.list li.collapsing.categories.post {list-style-type:none}
#sidebar ul.collapsing.categories.list li.collapsing.categories.post {
      }
#sidebar ul.collapsing.categories.list li.collapsing.categories.post .sym {
   font-size:1.2em;
   font-family:Monaco, 'Andale Mono', 'FreeMono', 'Courier new', 'Courier', monospace;
    float:left;
    padding-right:5px;
}
";

$noArrows="#sidebar span.collapsing.categories.post {
        border:0;
        padding:0; 
        margin:0; 
        cursor:pointer;
}
#sidebar li.collapsing.categories.post a.self {font-weight:bold}
#sidebar ul.collapsing.categories.list ul.collapsing.categories.list:before {content:'';} 
#sidebar ul.collapsing.categories.list li.collapsing.categories.post:before {content:'';} 
#sidebar ul.collapsing.categories.list li.collapsing.categories.post {list-style-type:none}
#sidebar ul.collapsing.categories.list li.collapsing.categories.post .sym {
   font-size:1.2em;
   font-family:Monaco, 'Andale Mono', 'FreeMono', 'Courier new', 'Courier', monospace;
    padding-right:5px;}";
$selected='default';
$custom=get_option('collapsCatStyle');
?>
