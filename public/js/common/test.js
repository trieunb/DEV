if ((negative || text.indexOf('-') > -1) && (event.which == 45)) {
			event.preventDefault();
		}  else if(text.indexOf('-')==-1 && event.which==45 && negative) {
	            $this.val('-'+text);
	        }