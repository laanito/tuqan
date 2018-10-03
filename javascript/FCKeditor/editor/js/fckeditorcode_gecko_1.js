﻿/*
 * FCKeditor - The text editor for internet
 * Copyright (C) 2003-2005 Frederico Caldeira Knabben
 * 
 * Licensed under the terms of the GNU Lesser General Public License:
 *         http://www.opensource.org/licenses/lgpl-license.php
 * 
 * For further information visit:
 *         http://www.fckeditor.net/
 * 
 * "Support Open Source software. What about a donation today?"
 * 
 * This file has been compacted for best loading performance.
 */
var FCKDebug = new Object();
if (FCKConfig.Debug) {
    FCKDebug.Output = function (A, B) {
        if (!FCKConfig.Debug) return;
        if (A != null && isNaN(A)) A = A.replace(/</g, "&lt;");
        if (!this.DebugWindow || this.DebugWindow.closed) this.DebugWindow = window.open('fckdebug.html', 'FCKeditorDebug', 'menubar=no,scrollbars=no,resizable=yes,location=no,toolbar=no,width=600,height=500', true);
        if (this.DebugWindow.Output) this.DebugWindow.Output(A, B);
    };
} else FCKDebug.Output = function () {
};
var FCKTools = new Object();
FCKTools.GetLinkedFieldValue = function () {
    return FCK.LinkedField.value;
};
FCKTools.AttachToLinkedFieldFormSubmit = function (A) {
    var B = FCK.LinkedField.form;
    if (!B) return;
    if (FCKBrowserInfo.IsIE) B.attachEvent("onsubmit", A); else B.addEventListener('submit', A, true);
    if (!B.updateFCKeditor) B.updateFCKeditor = new Array();
    B.updateFCKeditor[B.updateFCKeditor.length] = A;
    if (!B.originalSubmit && (typeof(B.submit) == 'function' || (!B.submit.tagName && !B.submit.length))) {
        B.originalSubmit = B.submit;
        B.submit = FCKTools_SubmitReplacer;
    }
    
};

function FCKTools_SubmitReplacer() {
    if (this.updateFCKeditor) {
        for (var i = 0; i < this.updateFCKeditor.length; i++) this.updateFCKeditor[i]();
    }
    this.originalSubmit();
}FCKTools.AddSelectOption = function (A, B, C, D) {
    var E = A.createElement("OPTION");
    E.text = C;
    E.value = D;
    B.options.add(E);
    return E;
};
FCKTools.RemoveAllSelectOptions = function (A) {
    for (var i = A.options.length - 1; i >= 0; i--) {
        A.options.remove(i);
    }
    
};
FCKTools.SelectNoCase = function (A, B, C) {
    var D = B.toString().toLowerCase();
    for (var i = 0; i < A.options.length; i++) {
        if (D == A.options[i].value.toLowerCase()) {
            A.selectedIndex = i;
            return;
        }
        
    }
    
    if (C != null) FCKTools.SelectNoCase(A, C);
};
FCKTools.HTMLEncode = function (A) {
    if (!A) return '';
    A = A.replace(/&/g, "&amp;");
    A = A.replace(/"/g, "&quot;");
    A = A.replace(/</g, "&lt;");
    A = A.replace(/>/g, "&gt;");
    A = A.replace(/'/g, "&#39;");
    return A;
};
FCKTools.GetResultingArray = function (A, B) {
    switch (typeof(A)) {
        case "string":
            return A.split(B);
        case "function":
            return B();
        default:
            if (isArray(A)) return A; else return new Array();
    }
    
};
FCKTools.GetElementPosition = function (A) {
    var c = {X: 0, Y: 0};
    while (A) {
        c.X += A.offsetLeft;
        c.Y += A.offsetTop;
        A = A.offsetParent;
    }
    
    return c;
};
FCKTools.GetElementAscensor = function (A, B) {
    var e = A;
    var C = "," + B.toUpperCase() + ",";
    while (e) {
        if (C.indexOf("," + e.nodeName.toUpperCase() + ",") != -1) return e;
        e = e.parentNode;
    }
    
    return null;
};
FCKTools.Pause = function (A) {
    var B = new Date();
    while (true) {
        var C = new Date();
        if (A < C - B) return;
    }
    
};
FCKTools.ConvertStyleSizeToHtml = function (A) {
    return A.endsWith('%') ? A : parseInt(A);
};
FCKTools.ConvertHtmlSizeToStyle = function (A) {
    return A.endsWith('%') ? A : (A + 'px');
};
var GECKO_BOGUS = '<br _moz_editor_bogus_node="TRUE">';
FCKTools.AppendStyleSheet = function (A, B) {
    var e = A.createElement('LINK');
    e.rel = 'stylesheet';
    e.type = 'text/css';
    e.href = B;
    A.getElementsByTagName("HEAD")[0].appendChild(e);
    return e;
};
FCKTools.ClearElementAttributes = function (A) {
    for (var i = 0; i < A.attributes.length; i++) {
        A.removeAttribute(A.attributes[i].name, 0);
    }
    
};
FCKTools.GetAllChildrenIds = function (A) {
    var B = new Array();
    var C = function (parent) {
        for (var i = 0; i < parent.childNodes.length; i++) {
            var D = parent.childNodes[i].id;
            if (D && D.length > 0) B[B.length] = D;
            C(parent.childNodes[i]);
        }
        
    };
    C(A);
    return B;
};
FCKTools.RemoveOuterTags = function (e) {
    var A = e.ownerDocument.createDocumentFragment();
    for (var i = 0; i < e.childNodes.length; i++) A.appendChild(e.childNodes[i]);
    e.parentNode.replaceChild(A, e);
};
FCKTools.CreateXmlObject = function (A) {
    switch (A) {
        case 'XmlHttp':
            return new XMLHttpRequest();
        case 'DOMDocument':
            return document.implementation.createDocument('', '', null);
    }
    
    return null;
};
var FCKRegexLib = new Object();
FCKRegexLib.AposEntity = /&apos;/gi;
FCKRegexLib.ObjectElements = /^(?:IMG|TABLE|TR|TD|INPUT|SELECT|TEXTAREA|HR|OBJECT)$/i;
FCKRegexLib.BlockElements = /^(?:P|DIV|H1|H2|H3|H4|H5|H6|ADDRESS|PRE|OL|UL|LI|TD)$/i;
FCKRegexLib.EmptyElements = /^(?:BASE|META|LINK|HR|BR|PARAM|IMG|AREA|INPUT)$/i;
FCKRegexLib.NamedCommands = /^(?:Cut|Copy|Paste|Print|SelectAll|RemoveFormat|Unlink|Undo|Redo|Bold|Italic|Underline|StrikeThrough|Subscript|Superscript|JustifyLeft|JustifyCenter|JustifyRight|JustifyFull|Outdent|Indent|InsertOrderedList|InsertUnorderedList|InsertHorizontalRule)$/i;
FCKRegexLib.BodyContents = /([\s\S]*\<body[^\>]*\>)([\s\S]*)(\<\/body\>[\s\S]*)/i;
FCKRegexLib.ToReplace = /___fcktoreplace:([\w]+)/ig;
FCKRegexLib.MetaHttpEquiv = /http-equiv\s*=\s*["']?([^"' ]+)/i;
FCKRegexLib.HasBaseTag = /<base /i;
FCKRegexLib.HeadOpener = /<head\s?[^>]*>/i;
FCKRegexLib.HeadCloser = /<\/head\s*>/i;
FCKRegexLib.TableBorderClass = /\s*FCK__ShowTableBorders\s*/;
FCKRegexLib.ElementName = /^[A-Za-z_:][\w.-:]*$/;
FCKRegexLib.ForceSimpleAmpersand = /___FCKAmp___/g;
FCKRegexLib.SpaceNoClose = /\/>/g;
FCKRegexLib.EmptyParagraph = /^<(p|div)>\s*<\/\1>$/i;
FCKRegexLib.TagBody = /></;
FCKRegexLib.StrongOpener = /<STRONG([ \>])/gi;
FCKRegexLib.StrongCloser = /<\/STRONG>/gi;
FCKRegexLib.EmOpener = /<EM([ \>])/gi;
FCKRegexLib.EmCloser = /<\/EM>/gi;
FCKRegexLib.GeckoEntitiesMarker = /#\?-\:/g;
FCKLanguageManager.GetActiveLanguage = function () {
    if (FCKConfig.AutoDetectLanguage) {
        var A;
        if (navigator.userLanguage) A = navigator.userLanguage.toLowerCase(); else if (navigator.language) A = navigator.language.toLowerCase(); else {
            return FCKConfig.DefaultLanguage;
        }
        
        if (A.length >= 5) {
            A = A.substr(0, 5);
            if (this.AvailableLanguages[A]) return A;
        }
        
        if (A.length >= 2) {
            A = A.substr(0, 2);
            if (this.AvailableLanguages[A]) return A;
        }
        
    }
    
    return this.DefaultLanguage;
};
FCKLanguageManager.TranslateElements = function (A, B, C) {
    var e = A.getElementsByTagName(B);
    for (var i = 0; i < e.length; i++) {
        var D = e[i].getAttribute('fckLang');
        if (D) {
            var s = FCKLang[D];
            if (s) eval('e[i].' + C + ' = s');
        }
        
    }
    
};
FCKLanguageManager.TranslatePage = function (A) {
    this.TranslateElements(A, 'INPUT', 'value');
    this.TranslateElements(A, 'SPAN', 'innerHTML');
    this.TranslateElements(A, 'LABEL', 'innerHTML');
    this.TranslateElements(A, 'OPTION', 'innerHTML');
};
if (FCKLanguageManager.AvailableLanguages[FCKConfig.DefaultLanguage]) FCKLanguageManager.DefaultLanguage = FCKConfig.DefaultLanguage; else FCKLanguageManager.DefaultLanguage = 'en';
FCKLanguageManager.ActiveLanguage = new Object();
FCKLanguageManager.ActiveLanguage.Code = FCKLanguageManager.GetActiveLanguage();
FCKLanguageManager.ActiveLanguage.Name = FCKLanguageManager.AvailableLanguages[FCKLanguageManager.ActiveLanguage.Code];
FCK.Language = FCKLanguageManager;
LoadLanguageFile();
var FCKEvents;
if (!(FCKEvents = NS.FCKEvents)) {
    FCKEvents = NS.FCKEvents = function (A) {
        this.Owner = A;
        this.RegisteredEvents = new Object();
    };
    FCKEvents.prototype.AttachEvent = function (A, B) {
        if (!this.RegisteredEvents[A]) this.RegisteredEvents[A] = new Array();
        this.RegisteredEvents[A][this.RegisteredEvents[A].length] = B;
    };
    FCKEvents.prototype.FireEvent = function (A, B) {
        var C = true;
        var D = this.RegisteredEvents[A];
        if (D) {
            for (var i = 0; i < D.length; i++) C = (D[i](this.Owner, B) && C);
        }
        
        return C;
    };
}
var FCKXHtmlEntities = new Object();
if (FCKConfig.ProcessHTMLEntities) {
    FCKXHtmlEntities.Entities = {
        ' ': 'nbsp',
        '¡': 'iexcl',
        '¢': 'cent',
        '£': 'pound',
        '¤': 'curren',
        '¥': 'yen',
        '¦': 'brvbar',
        '§': 'sect',
        '¨': 'uml',
        '©': 'copy',
        'ª': 'ordf',
        '«': 'laquo',
        '¬': 'not',
        '­': 'shy',
        '®': 'reg',
        '¯': 'macr',
        '°': 'deg',
        '±': 'plusmn',
        '²': 'sup2',
        '³': 'sup3',
        '´': 'acute',
        'µ': 'micro',
        '¶': 'para',
        '·': 'middot',
        '¸': 'cedil',
        '¹': 'sup1',
        'º': 'ordm',
        '»': 'raquo',
        '¼': 'frac14',
        '½': 'frac12',
        '¾': 'frac34',
        '¿': 'iquest',
        '×': 'times',
        '÷': 'divide',
        'ƒ': 'fnof',
        '•': 'bull',
        '…': 'hellip',
        '′': 'prime',
        '″': 'Prime',
        '‾': 'oline',
        '⁄': 'frasl',
        '℘': 'weierp',
        'ℑ': 'image',
        'ℜ': 'real',
        '™': 'trade',
        'ℵ': 'alefsym',
        '←': 'larr',
        '↑': 'uarr',
        '→': 'rarr',
        '↓': 'darr',
        '↔': 'harr',
        '↵': 'crarr',
        '⇐': 'lArr',
        '⇑': 'uArr',
        '⇒': 'rArr',
        '⇓': 'dArr',
        '⇔': 'hArr',
        '∀': 'forall',
        '∂': 'part',
        '∃': 'exist',
        '∅': 'empty',
        '∇': 'nabla',
        '∈': 'isin',
        '∉': 'notin',
        '∋': 'ni',
        '∏': 'prod',
        '∑': 'sum',
        '−': 'minus',
        '∗': 'lowast',
        '√': 'radic',
        '∝': 'prop',
        '∞': 'infin',
        '∠': 'ang',
        '∧': 'and',
        '∨': 'or',
        '∩': 'cap',
        '∪': 'cup',
        '∫': 'int',
        '∴': 'there4',
        '∼': 'sim',
        '≅': 'cong',
        '≈': 'asymp',
        '≠': 'ne',
        '≡': 'equiv',
        '≤': 'le',
        '≥': 'ge',
        '⊂': 'sub',
        '⊃': 'sup',
        '⊄': 'nsub',
        '⊆': 'sube',
        '⊇': 'supe',
        '⊕': 'oplus',
        '⊗': 'otimes',
        '⊥': 'perp',
        '⋅': 'sdot',
        '◊': 'loz',
        '♠': 'spades',
        '♣': 'clubs',
        '♥': 'hearts',
        '♦': 'diams',
        '"': 'quot',
        'ˆ': 'circ',
        '˜': 'tilde',
        ' ': 'ensp',
        ' ': 'emsp',
        ' ': 'thinsp',
        '‌': 'zwnj',
        '‍': 'zwj',
        '‎': 'lrm',
        '‏': 'rlm',
        '–': 'ndash',
        '—': 'mdash',
        '‘': 'lsquo',
        '’': 'rsquo',
        '‚': 'sbquo',
        '“': 'ldquo',
        '”': 'rdquo',
        '„': 'bdquo',
        '†': 'dagger',
        '‡': 'Dagger',
        '‰': 'permil',
        '‹': 'lsaquo',
        '›': 'rsaquo',
        '€': 'euro'
    };
    FCKXHtmlEntities.Chars = '';
    for (var e in FCKXHtmlEntities.Entities) FCKXHtmlEntities.Chars += e;
    if (FCKConfig.IncludeLatinEntities) {
        var oEntities = {
            'À': 'Agrave',
            'Á': 'Aacute',
            'Â': 'Acirc',
            'Ã': 'Atilde',
            'Ä': 'Auml',
            'Å': 'Aring',
            'Æ': 'AElig',
            'Ç': 'Ccedil',
            'È': 'Egrave',
            'É': 'Eacute',
            'Ê': 'Ecirc',
            'Ë': 'Euml',
            'Ì': 'Igrave',
            'Í': 'Iacute',
            'Î': 'Icirc',
            'Ï': 'Iuml',
            'Ð': 'ETH',
            'Ñ': 'Ntilde',
            'Ò': 'Ograve',
            'Ó': 'Oacute',
            'Ô': 'Ocirc',
            'Õ': 'Otilde',
            'Ö': 'Ouml',
            'Ø': 'Oslash',
            'Ù': 'Ugrave',
            'Ú': 'Uacute',
            'Û': 'Ucirc',
            'Ü': 'Uuml',
            'Ý': 'Yacute',
            'Þ': 'THORN',
            'ß': 'szlig',
            'à': 'agrave',
            'á': 'aacute',
            'â': 'acirc',
            'ã': 'atilde',
            'ä': 'auml',
            'å': 'aring',
            'æ': 'aelig',
            'ç': 'ccedil',
            'è': 'egrave',
            'é': 'eacute',
            'ê': 'ecirc',
            'ë': 'euml',
            'ì': 'igrave',
            'í': 'iacute',
            'î': 'icirc',
            'ï': 'iuml',
            'ð': 'eth',
            'ñ': 'ntilde',
            'ò': 'ograve',
            'ó': 'oacute',
            'ô': 'ocirc',
            'õ': 'otilde',
            'ö': 'ouml',
            'ø': 'oslash',
            'ù': 'ugrave',
            'ú': 'uacute',
            'û': 'ucirc',
            'ü': 'uuml',
            'ý': 'yacute',
            'þ': 'thorn',
            'ÿ': 'yuml',
            'Œ': 'OElig',
            'œ': 'oelig',
            'Š': 'Scaron',
            'š': 'scaron',
            'Ÿ': 'Yuml'
        };
        for (var e in oEntities) {
            FCKXHtmlEntities.Entities[e] = oEntities[e];
            FCKXHtmlEntities.Chars += e;
        }
        oEntities = null;
    }
    
    if (FCKConfig.IncludeGreekEntities) {
        var oEntities = {
            'Α': 'Alpha',
            'Β': 'Beta',
            'Γ': 'Gamma',
            'Δ': 'Delta',
            'Ε': 'Epsilon',
            'Ζ': 'Zeta',
            'Η': 'Eta',
            'Θ': 'Theta',
            'Ι': 'Iota',
            'Κ': 'Kappa',
            'Λ': 'Lambda',
            'Μ': 'Mu',
            'Ν': 'Nu',
            'Ξ': 'Xi',
            'Ο': 'Omicron',
            'Π': 'Pi',
            'Ρ': 'Rho',
            'Σ': 'Sigma',
            'Τ': 'Tau',
            'Υ': 'Upsilon',
            'Φ': 'Phi',
            'Χ': 'Chi',
            'Ψ': 'Psi',
            'Ω': 'Omega',
            'α': 'alpha',
            'β': 'beta',
            'γ': 'gamma',
            'δ': 'delta',
            'ε': 'epsilon',
            'ζ': 'zeta',
            'η': 'eta',
            'θ': 'theta',
            'ι': 'iota',
            'κ': 'kappa',
            'λ': 'lambda',
            'μ': 'mu',
            'ν': 'nu',
            'ξ': 'xi',
            'ο': 'omicron',
            'π': 'pi',
            'ρ': 'rho',
            'ς': 'sigmaf',
            'σ': 'sigma',
            'τ': 'tau',
            'υ': 'upsilon',
            'φ': 'phi',
            'χ': 'chi',
            'ψ': 'psi',
            'ω': 'omega'
        };
        for (var e in oEntities) {
            FCKXHtmlEntities.Entities[e] = oEntities[e];
            FCKXHtmlEntities.Chars += e;
        }
        oEntities = null;
    }
    FCKXHtmlEntities.EntitiesRegex = new RegExp('', '');
    FCKXHtmlEntities.EntitiesRegex.compile('[' + FCKXHtmlEntities.Chars + ']|[^' + FCKXHtmlEntities.Chars + ']+', 'g');
} else {
    FCKXHtmlEntities.Entities = {' ': 'nbsp'};
    FCKXHtmlEntities.EntitiesRegex = /[ ]|[^ ]+/g;
}
var FCKXHtml = new Object();
FCKXHtml.CurrentJobNum = 0;
FCKXHtml.GetXHTML = function (A, B, C) {
    FCKXHtml.SpecialBlocks = new Array();
    this.XML = FCKTools.CreateXmlObject('DOMDocument');
    this.MainNode = this.XML.appendChild(this.XML.createElement('xhtml'));
    FCKXHtml.CurrentJobNum++;
    if (B) this._AppendNode(this.MainNode, A); else this._AppendChildNodes(this.MainNode, A, false);
    var D = this._GetMainXmlString();
    D = D.substr(7, D.length - 15).trim();
    if (FCKBrowserInfo.IsGecko) D = D.replace(/<br\/>$/, '');
    D = D.replace(FCKRegexLib.SpaceNoClose, ' />');
    if (FCKConfig.ForceSimpleAmpersand) D = D.replace(FCKRegexLib.ForceSimpleAmpersand, '&');
    if (C) D = FCKCodeFormatter.Format(D);
    for (var i = 0; i < FCKXHtml.SpecialBlocks.length; i++) {
        var E = new RegExp('___FCKsi___' + i);
        D = D.replace(E, FCKXHtml.SpecialBlocks[i]);
    }
    this.XML = null;
    return D
};
FCKXHtml._AppendAttribute = function (A, B, C) {
    try {
        var D = this.XML.createAttribute(B);
        D.value = C ? C : '';
        A.attributes.setNamedItem(D);
    } catch (e) {
    }
    
};
FCKXHtml._AppendChildNodes = function (A, B, C) {
    var D = 0;
    if (B.hasChildNodes()) {
        var E = B.childNodes;
        for (var i = 0; i < E.length; i++) {
            if (this._AppendNode(A, E[i])) D++;
        }
        
    }
    
    if (D == 0) {
        if (C && FCKConfig.FillEmptyBlocks) {
            this._AppendEntity(A, 'nbsp');
            return;
        }
        
        if (!FCKRegexLib.EmptyElements.test(B.nodeName)) A.appendChild(this.XML.createTextNode(''));
    }
    
};
FCKXHtml._AppendNode = function (A, B) {
    switch (B.nodeType) {
        case 1:
            if (B.getAttribute('_fckfakelement')) return FCKXHtml._AppendNode(A, FCK.GetRealElement(B));
            if (FCKBrowserInfo.IsGecko && B.hasAttribute('_moz_editor_bogus_node')) return false;
            if (B.getAttribute('_fckdelete')) return false;
            var C = B.nodeName;
            if (!FCKRegexLib.ElementName.test(C)) return false;
            C = C.toLowerCase();
            if (FCKBrowserInfo.IsGecko && C == 'br' && B.hasAttribute('type') && B.getAttribute('type', 2) == '_moz') return false;
            if (B._fckxhtmljob && B._fckxhtmljob == FCKXHtml.CurrentJobNum) return false; else B._fckxhtmljob = FCKXHtml.CurrentJobNum;
            var D = this._CreateNode(C);
            FCKXHtml._AppendAttributes(A, B, D, C);
            var E = FCKXHtml.TagProcessors[C];
            if (E) {
                D = E(D, B);
                if (!D) break;
            } else this._AppendChildNodes(D, B, FCKRegexLib.BlockElements.test(C));
            A.appendChild(D);
            break;
        case 3:
            this._AppendTextNode(A, B.nodeValue.replaceNewLineChars(' '));
            break;
        case 8:
            try {
                A.appendChild(this.XML.createComment(B.nodeValue));
            } catch (e) { /* Do nothing... probably this is a wrong format comment. */
            }
            
            break;
        default:
            A.appendChild(this.XML.createComment("Element not supported - Type: " + B.nodeType + " Name: " + B.nodeName));
            break;
    }
    
    return true;
};
if (FCKConfig.ForceStrongEm) {
    FCKXHtml._CreateNode = function (A) {
        switch (A) {
            case 'b':
                A = 'strong';
                break;
            case 'i':
                A = 'em';
                break;
        }
        
        return this.XML.createElement(A);
    };
} else {
    FCKXHtml._CreateNode = function (A) {
        return this.XML.createElement(A);
    };
}
FCKXHtml._AppendSpecialItem = function (A) {
    return '___FCKsi___' + FCKXHtml.SpecialBlocks.addItem(A);
};
FCKXHtml._AppendTextNode = function (A, B) {
    var C = B.match(FCKXHtmlEntities.EntitiesRegex);
    if (C) {
        for (var i = 0; i < C.length; i++) {
            if (C[i].length == 1) {
                var D = FCKXHtmlEntities.Entities[C[i]];
                if (D != null) {
                    this._AppendEntity(A, D);
                    continue;
                }
                
            }
            A.appendChild(this.XML.createTextNode(C[i]));
        }
        
    }
    
};
FCKXHtml.TagProcessors = new Object();
FCKXHtml.TagProcessors['img'] = function (A) {
    if (!A.attributes.getNamedItem('alt')) FCKXHtml._AppendAttribute(A, 'alt', '');
    return A;
};
FCKXHtml.TagProcessors['script'] = function (A, B) {
    if (!A.attributes.getNamedItem('type')) FCKXHtml._AppendAttribute(A, 'type', 'text/javascript');
    A.appendChild(FCKXHtml.XML.createTextNode(FCKXHtml._AppendSpecialItem(B.text)));
    return A;
};
FCKXHtml.TagProcessors['style'] = function (A, B) {
    if (B.getAttribute('_fcktemp')) return null;
    if (!A.attributes.getNamedItem('type')) FCKXHtml._AppendAttribute(A, 'type', 'text/css');
    A.appendChild(FCKXHtml.XML.createTextNode(FCKXHtml._AppendSpecialItem(B.innerHTML)));
    return A;
};
FCKXHtml.TagProcessors['title'] = function (A, B) {
    A.appendChild(FCKXHtml.XML.createTextNode(FCK.EditorDocument.title));
    return A;
};
FCKXHtml.TagProcessors['base'] = function (A, B) {
    if (B.getAttribute('_fcktemp')) return null;
    return A;
};
FCKXHtml.TagProcessors['link'] = function (A, B) {
    if (B.getAttribute('_fcktemp')) return null;
    return A;
};
FCKXHtml.TagProcessors['table'] = function (A, B) {
    var C = A.attributes.getNamedItem('class');
    if (C && FCKRegexLib.TableBorderClass.test(C.nodeValue)) {
        var D = C.nodeValue.replace(FCKRegexLib.TableBorderClass, '');
        if (D.length == 0) A.attributes.removeNamedItem('class'); else FCKXHtml._AppendAttribute(A, 'class', D);
    }
    FCKXHtml._AppendChildNodes(A, B, false);
    return A;
}
FCKXHtml._GetMainXmlString = function () {
    var A = new XMLSerializer();
    return A.serializeToString(this.MainNode).replace(FCKRegexLib.GeckoEntitiesMarker, '&');
};
FCKXHtml._AppendEntity = function (A, B) {
    A.appendChild(this.XML.createTextNode('#?-:' + B + ';'));
};
FCKXHtml._AppendAttributes = function (A, B, C) {
    var D = B.attributes;
    for (var n = 0; n < D.length; n++) {
        var E = D[n];
        if (E.specified) {
            var F = E.nodeName.toLowerCase();
            var G;
            if (F == '_fckxhtmljob') continue; else if (F.indexOf('_moz') == 0) continue; else if (F == 'class') G = E.nodeValue; else if (E.nodeValue === true) G = F; else G = B.getAttribute(F, 2);
            if (FCKConfig.ForceSimpleAmpersand && G.replace) G = G.replace(/&/g, '___FCKAmp___');
            this._AppendAttribute(C, F, G);
        }
        
    }
    
}
var FCKCodeFormatter;
if (!(FCKCodeFormatter = NS.FCKCodeFormatter)) {
    FCKCodeFormatter = NS.FCKCodeFormatter = new Object();
    FCKCodeFormatter.Regex = new Object();
    FCKCodeFormatter.Regex.BlocksOpener = /\<(P|DIV|H1|H2|H3|H4|H5|H6|ADDRESS|PRE|OL|UL|LI|TITLE|META|LINK|BASE|SCRIPT|LINK|TD|AREA|OPTION)[^\>]*\>/gi;
    FCKCodeFormatter.Regex.BlocksCloser = /\<\/(P|DIV|H1|H2|H3|H4|H5|H6|ADDRESS|PRE|OL|UL|LI|TITLE|META|LINK|BASE|SCRIPT|LINK|TD|AREA|OPTION)[^\>]*\>/gi;
    FCKCodeFormatter.Regex.NewLineTags = /\<(BR|HR)[^\>]\>/gi;
    FCKCodeFormatter.Regex.MainTags = /\<\/?(HTML|HEAD|BODY|FORM|TABLE|TBODY|THEAD|TR)[^\>]*\>/gi;
    FCKCodeFormatter.Regex.LineSplitter = /\s*\n+\s*/g;
    FCKCodeFormatter.Regex.IncreaseIndent = /^\<(HTML|HEAD|BODY|FORM|TABLE|TBODY|THEAD|TR|UL|OL)[ \/\>]/i;
    FCKCodeFormatter.Regex.DecreaseIndent = /^\<\/(HTML|HEAD|BODY|FORM|TABLE|TBODY|THEAD|TR|UL|OL)[ \>]/i;
    FCKCodeFormatter.Regex.FormatIndentatorRemove = new RegExp(FCKConfig.FormatIndentator);
    FCKCodeFormatter.Format = function (A) {
        var B = A.replace(this.Regex.BlocksOpener, '\n$&');
        B = B.replace(this.Regex.BlocksCloser, '$&\n');
        B = B.replace(this.Regex.NewLineTags, '$&\n');
        B = B.replace(this.Regex.MainTags, '\n$&\n');
        var C = '';
        var D = B.split(this.Regex.LineSplitter);
        B = '';
        for (var i = 0; i < D.length; i++) {
            var E = D[i];
            if (E.length == 0) continue;
            if (this.Regex.DecreaseIndent.test(E)) C = C.replace(this.Regex.FormatIndentatorRemove, '');
            B += C + E + '\n';
            if (this.Regex.IncreaseIndent.test(E)) C += FCKConfig.FormatIndentator;
        }
        
        return B.trim();
    };
}
var FCKUndo = new Object();
FCKUndo.SaveUndoStep = function () {
}
FCK.Events = new FCKEvents(FCK);
FCK.Toolbar = null;
FCK.TempBaseTag = FCKConfig.BaseHref.length > 0 ? '<base href="' + FCKConfig.BaseHref + '" _fcktemp="true"></base>' : '';
FCK.StartEditor = function () {
    this.EditorWindow = window.frames['eEditorArea'];
    this.EditorDocument = this.EditorWindow.document;
    this.SetHTML(FCKTools.GetLinkedFieldValue());
    FCKTools.AttachToLinkedFieldFormSubmit(this.UpdateLinkedField);
    FCKUndo.SaveUndoStep();
    this.SetStatus(FCK_STATUS_ACTIVE);
};

function Window_OnFocus() {
    FCK.Focus();
}FCK.SetStatus = function (A) {
    this.Status = A;
    if (A == FCK_STATUS_ACTIVE) {
        window.onfocus = window.document.body.onfocus = Window_OnFocus;
        if (FCKConfig.StartupFocus) FCK.Focus();
        if (FCKBrowserInfo.IsIE) FCKScriptLoader.AddScript('js/fckeditorcode_ie_2.js'); else FCKScriptLoader.AddScript('js/fckeditorcode_gecko_2.js');
    }
    this.Events.FireEvent('OnStatusChange', A);
};
FCK.GetHTML = function (A) {
    var B;
    if (FCK.EditMode == FCK_EDITMODE_WYSIWYG) {
        if (FCKBrowserInfo.IsIE) B = this.EditorDocument.body.innerHTML.replace(FCKRegexLib.ToReplace, '$1'); else B = this.EditorDocument.body.innerHTML;
    } else B = document.getElementById('eSourceField').value;
    if (A) return FCKCodeFormatter.Format(B); else return B;
};
FCK.GetXHTML = function (A) {
    var B = (FCK.EditMode == FCK_EDITMODE_SOURCE);
    if (B) this.SwitchEditMode();
    var C;
    if (FCKConfig.FullPage) C = FCKXHtml.GetXHTML(this.EditorDocument.getElementsByTagName('html')[0], true, A); else {
        if (FCKConfig.IgnoreEmptyParagraphValue && this.EditorDocument.body.innerHTML == '<P>&nbsp;</P>') C = ''; else C = FCKXHtml.GetXHTML(this.EditorDocument.body, false, A);
    }
    
    if (B) this.SwitchEditMode();
    if (FCKBrowserInfo.IsIE) C = C.replace(FCKRegexLib.ToReplace, '$1');
    if (FCK.DocTypeDeclaration && FCK.DocTypeDeclaration.length > 0) C = FCK.DocTypeDeclaration + '\n' + C;
    if (FCK.XmlDeclaration && FCK.XmlDeclaration.length > 0) C = FCK.XmlDeclaration + '\n' + C;
    return FCKConfig.ProtectedSource.Revert(C);
};
FCK.UpdateLinkedField = function () {
    if (FCKConfig.EnableXHTML) FCK.LinkedField.value = FCK.GetXHTML(FCKConfig.FormatOutput); else FCK.LinkedField.value = FCK.GetHTML(FCKConfig.FormatOutput);
    FCK.Events.FireEvent('OnAfterLinkedFieldUpdate');
};
FCK.ShowContextMenu = function (x, y) {
    if (this.Status != FCK_STATUS_COMPLETE) return;
    FCKContextMenu.Show(x, y);
    this.Events.FireEvent("OnContextMenu");
};
FCK.RegisteredDoubleClickHandlers = new Object();
FCK.OnDoubleClick = function (A) {
    var B = FCK.RegisteredDoubleClickHandlers[A.tagName];
    if (B) B(A);
};
FCK.RegisterDoubleClickHandler = function (A, B) {
    FCK.RegisteredDoubleClickHandlers[B.toUpperCase()] = A;
};
FCK.OnAfterSetHTML = function () {
    var A, i = 0;
    while ((A = FCKDocumentProcessors[i++])) A.ProcessDocument(FCK.EditorDocument);
    this.Events.FireEvent('OnAfterSetHTML');
};
var FCKDocumentProcessors = new Array();
var FCKDocumentProcessors_CreateFakeImage = function (A, B) {
    var C = FCK.EditorDocument.createElement('IMG');
    C.className = A;
    C.src = FCKConfig.FullBasePath + 'images/spacer.gif';
    C.setAttribute('_fckfakelement', 'true', 0);
    C.setAttribute('_fckrealelement', FCKTempBin.AddElement(B), 0);
    return C;
};
var FCKAnchorsProcessor = new Object();
FCKAnchorsProcessor.ProcessDocument = function (A) {
    var B = A.getElementsByTagName('A');
    var C;
    var i = B.length - 1;
    while (i >= 0 && (C = B[i--])) {
        if (C.name.length > 0 && (!C.getAttribute('href') || C.getAttribute('href').length == 0)) {
            var D = FCKDocumentProcessors_CreateFakeImage('FCK__Anchor', C.cloneNode(true));
            D.setAttribute('_fckanchor', 'true', 0);
            C.parentNode.insertBefore(D, C);
            C.parentNode.removeChild(C);
        }
        
    }
    
};
FCKDocumentProcessors.addItem(FCKAnchorsProcessor);
var FCKPageBreaksProcessor = new Object();
FCKPageBreaksProcessor.ProcessDocument = function (A) {
    var B = A.getElementsByTagName('CENTER');
    var C;
    var i = B.length - 1;
    while (i >= 0 && (C = B[i--])) {
        if (C.style.pageBreakAfter == 'always' && C.innerHTML.trim().length == 0) {
            var D = FCKDocumentProcessors_CreateFakeImage('FCK__PageBreak', C.cloneNode(true));
            C.parentNode.insertBefore(D, C);
            C.parentNode.removeChild(C);
        }
        
    }
    
};
FCKDocumentProcessors.addItem(FCKPageBreaksProcessor);
var FCKFlashProcessor = new Object();
FCKFlashProcessor.ProcessDocument = function (A) {
    var B = A.getElementsByTagName('EMBED');
    var C;
    var i = B.length - 1;
    while (i >= 0 && (C = B[i--])) {
        if (C.src.endsWith('.swf', true)) {
            var D = FCKDocumentProcessors_CreateFakeImage('FCK__Flash', C.cloneNode(true));
            D.setAttribute('_fckflash', 'true', 0);
            FCKFlashProcessor.RefreshView(D, C);
            C.parentNode.insertBefore(D, C);
            C.parentNode.removeChild(C);
        }
        
    }
    
};
FCKFlashProcessor.RefreshView = function (A, B) {
    if (B.width > 0) A.style.width = FCKTools.ConvertHtmlSizeToStyle(B.width);
    if (B.height > 0) A.style.height = FCKTools.ConvertHtmlSizeToStyle(B.height);
};
FCKDocumentProcessors.addItem(FCKFlashProcessor);
FCK.GetRealElement = function (A) {
    var e = FCKTempBin.Elements[A.getAttribute('_fckrealelement')];
    if (A.getAttribute('_fckflash')) {
        if (A.style.width.length > 0) e.width = FCKTools.ConvertStyleSizeToHtml(A.style.width);
        if (A.style.height.length > 0) e.height = FCKTools.ConvertStyleSizeToHtml(A.style.height);
    }
    
    return e;
};
FCK.Description = "FCKeditor for Gecko Browsers";
FCK.InitializeBehaviors = function () {
    if (FCKConfig.ShowBorders) {
        var A = FCKTools.AppendStyleSheet(this.EditorDocument, FCKConfig.FullBasePath + 'css/fck_showtableborders_gecko.css');
        A.setAttribute('_fcktemp', 'true');
    }
    var B = function (e) {
        e.preventDefault();
        FCK.ShowContextMenu(e.clientX, e.clientY);
    };
    this.EditorDocument.addEventListener('contextmenu', B, true);
    var C = function (e) {
        if (e.ctrlKey && !e.shiftKey && !e.altKey) {
            var D = false;
            switch (e.which) {
                case 66:
                case 98:
                    FCK.ExecuteNamedCommand('bold');
                    D = true;
                    break;
                case 105:
                case 73:
                    FCK.ExecuteNamedCommand('italic');
                    D = true;
                    break;
                case 117:
                case 85:
                    FCK.ExecuteNamedCommand('underline');
                    D = true;
                    break;
                case 86:
                case 118:
                    D = (FCK.Status != FCK_STATUS_COMPLETE || !FCK.Events.FireEvent("OnPaste"));
                    break;
            }
            
            if (D) {
                e.preventDefault();
                e.stopPropagation();
            }
            
        }
        
    };
    this.EditorDocument.addEventListener('keypress', C, true);
    this.ExecOnSelectionChange = function () {
        FCK.Events.FireEvent("OnSelectionChange");
    };
    this.ExecOnSelectionChangeTimer = function () {
        if (FCK.LastOnChangeTimer) window.clearTimeout(FCK.LastOnChangeTimer);
        FCK.LastOnChangeTimer = window.setTimeout(FCK.ExecOnSelectionChange, 100);
    };
    this.EditorDocument.addEventListener('mouseup', this.ExecOnSelectionChange, false);
    this.EditorDocument.addEventListener('keyup', this.ExecOnSelectionChangeTimer, false);
    this._DblClickListener = function (e) {
        FCK.OnDoubleClick(e.target);
        e.stopPropagation();
    };
    this.EditorDocument.addEventListener('dblclick', this._DblClickListener, true);
    this._OnLoad = function () {
        if (this._FCK_HTML) {
            this.document.body.innerHTML = this._FCK_HTML;
            this._FCK_HTML = null;
        }
        
    };
    this.EditorWindow.addEventListener('load', this._OnLoad, true);
};
FCK.MakeEditable = function () {
    try {
        FCK.EditorDocument.designMode = 'on';
        FCK.EditorDocument.execCommand('useCSS', false, !FCKConfig.GeckoUseSPAN);
    } catch (e) {
    }
    
};
FCK.Focus = function () {
    try {
        FCK.EditorWindow.focus();
    } catch (e) {
    }
    
};
FCK.SetHTML = function (A, B) {
    A = A.replace(FCKRegexLib.StrongOpener, '<b$1');
    A = A.replace(FCKRegexLib.StrongCloser, '<\/b>');
    A = A.replace(FCKRegexLib.EmOpener, '<i$1');
    A = A.replace(FCKRegexLib.EmCloser, '<\/i>');
    if (B || FCK.EditMode == FCK_EDITMODE_WYSIWYG) {
        A = FCKConfig.ProtectedSource.Protect(A);
        if (FCKConfig.FullPage && FCKRegexLib.BodyContents.test(A)) {
            if (FCK.TempBaseTag.length > 0 && !FCKRegexLib.HasBaseTag.test(A)) A = A.replace(FCKRegexLib.HeadOpener, '$&' + FCK.TempBaseTag);
            A = A.replace(FCKRegexLib.HeadCloser, '<link href="' + FCKConfig.BasePath + 'css/fck_internal.css' + '" rel="stylesheet" type="text/css" _fcktemp="true" /></head>');
            var C = A.match(FCKRegexLib.BodyContents);
            var D = C[1];
            var E = C[2];
            var F = C[3];
            var G = D + '&nbsp;' + F;
            if (!this._Initialized) {
                FCK.EditorDocument.designMode = "on";
                FCK.EditorDocument.execCommand("useCSS", false, !FCKConfig.GeckoUseSPAN);
                this._Initialized = true;
            }
            this.EditorDocument.open();
            this.EditorDocument.write(G);
            this.EditorDocument.close();
            if (this.EditorDocument.body) this.EditorDocument.body.innerHTML = E; else this.EditorWindow._FCK_HTML = E;
            this.InitializeBehaviors();
        } else {
            if (!this._Initialized) {
                this.EditorDocument.dir = FCKConfig.ContentLangDirection;
                var G = '<title></title>' + '<link href="' + FCKConfig.EditorAreaCSS + '" rel="stylesheet" type="text/css" />' + '<link href="' + FCKConfig.BasePath + 'css/fck_internal.css' + '" rel="stylesheet" type="text/css" _fcktemp="true" />' + FCK.TempBaseTag;
                this.EditorDocument.getElementsByTagName("HEAD")[0].innerHTML = G;
                this.InitializeBehaviors();
                this._Initialized = true;
            }
            
            if (A.length == 0) FCK.EditorDocument.body.innerHTML = GECKO_BOGUS; else if (FCKRegexLib.EmptyParagraph.test(A)) FCK.EditorDocument.body.innerHTML = A.replace(FCKRegexLib.TagBody, '>' + GECKO_BOGUS + '<'); else FCK.EditorDocument.body.innerHTML = A;
            FCK.MakeEditable();
        }
        FCK.OnAfterSetHTML();
    } else document.getElementById('eSourceField').value = A;
}
