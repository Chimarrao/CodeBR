import Prism from 'prismjs';

import 'prismjs/components/prism-markup';
import 'prismjs/components/prism-css';
import 'prismjs/components/prism-clike';
import 'prismjs/components/prism-javascript';
import 'prismjs/components/prism-apacheconf';
import 'prismjs/components/prism-c';
import 'prismjs/components/prism-csharp';
import 'prismjs/components/prism-cpp';
import 'prismjs/components/prism-coffeescript';
import 'prismjs/components/prism-css-extras';
import 'prismjs/components/prism-csv';
import 'prismjs/components/prism-diff';
import 'prismjs/components/prism-erlang';
import 'prismjs/components/prism-fortran';
import 'prismjs/components/prism-go';
import 'prismjs/components/prism-http';
import 'prismjs/components/prism-hpkp';
import 'prismjs/components/prism-hsts';
import 'prismjs/components/prism-java';
// import 'prismjs/components/prism-javadoc';
import 'prismjs/components/prism-javadoclike';
import 'prismjs/components/prism-javastacktrace';
// import 'prismjs/components/prism-jsdoc';
// import 'prismjs/components/prism-js-extras';
import 'prismjs/components/prism-json';
import 'prismjs/components/prism-json5';
import 'prismjs/components/prism-jsonp';
import 'prismjs/components/prism-jsstacktrace';
import 'prismjs/components/prism-js-templates';
import 'prismjs/components/prism-markdown';
import 'prismjs/components/prism-markup-templating';
import 'prismjs/components/prism-nginx';
import 'prismjs/components/prism-php';
// import 'prismjs/components/prism-phpdoc';
import 'prismjs/components/prism-php-extras';
// import 'prismjs/components/prism-plsql';
import 'prismjs/components/prism-powershell';
import 'prismjs/components/prism-python';
import 'prismjs/components/prism-r';
import 'prismjs/components/prism-jsx';
import 'prismjs/components/prism-tsx';
import 'prismjs/components/prism-regex';
import 'prismjs/components/prism-ruby';
import 'prismjs/components/prism-rust';
import 'prismjs/components/prism-sql';
import 'prismjs/components/prism-typescript';

import 'prismjs/plugins/autolinker/prism-autolinker';
import 'prismjs/plugins/inline-color/prism-inline-color';
import 'prismjs/plugins/command-line/prism-command-line';
import 'prismjs/plugins/unescaped-markup/prism-unescaped-markup';
import 'prismjs/plugins/normalize-whitespace/prism-normalize-whitespace';
import 'prismjs/plugins/toolbar/prism-toolbar';
import 'prismjs/plugins/copy-to-clipboard/prism-copy-to-clipboard';
import 'prismjs/plugins/match-braces/prism-match-braces';
import 'prismjs/plugins/diff-highlight/prism-diff-highlight';
import 'prismjs/plugins/treeview/prism-treeview';

document.addEventListener("DOMContentLoaded", function() {
    setTimeout(() => {
        Prism.highlightAll();
    }, 1000);
});

/* PrismJS 1.29.0
https://prismjs.com/download.html#themes=prism-okaidia&languages=markup+css+clike+javascript+apacheconf+c+csharp+cpp+coffeescript+css-extras+csv+diff+erlang+fortran+go+http+hpkp+hsts+java+javadoc+javadoclike+javastacktrace+jsdoc+js-extras+json+json5+jsonp+jsstacktrace+js-templates+markdown+markup-templating+nginx+php+phpdoc+php-extras+plsql+powershell+python+r+jsx+tsx+regex+ruby+rust+sql+typescript&plugins=autolinker+inline-color+command-line+unescaped-markup+normalize-whitespace+toolbar+copy-to-clipboard+match-braces+diff-highlight+treeview */