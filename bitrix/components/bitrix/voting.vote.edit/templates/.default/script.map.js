{"version":3,"file":"script.min.js","sources":["script.js"],"names":["window","BVoteConstructor","top","Params","this","controller","maxQ","parseInt","maxA","q","num","cnt","a","InitVoteForm","prototype","checkAnswerAdding","qId","nodeQuestion","BX","list","firstChild","unbindAll","node","unbind","proxy","_do","nextSibling","hide","show","lastChild","bind","checkQuestionAdding","style","display","vOl","findChild","tagName","className","vLi","childNodes","regexp","exec","id","length","aOl","aLi","regexpa","ii","findChildren","nodeTags","nodeTag","hasOwnProperty","parentNode","delegate","e","PreventDefault","reg","proxy_context","res","qOl","qLi","findParent","getAttribute","create","html","message","replace","appendChild","value","confirm","removeChild","tag","focus","question","previousSibling"],"mappings":"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"}