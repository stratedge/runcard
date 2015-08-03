PATH=$HOME/bin:$PATH

##
# git branch printing
##
parse_git_branch() {
	git branch 2> /dev/null 1> /dev/null
	if [ $? = 0 ]; then
		BRANCH=`git branch 2> /dev/null | sed -e '/^[^*]/d' -e 's/* \(.*\)/:\1/' >&1`
		git status | grep "nothing to commit" > /dev/null
		if [ $? = 0 ]; then
			STATUS=""
		else
			STATUS="⚡"
		fi
		echo "$BRANCH$STATUS "
	fi
}

# Reset
COLOROFF="\[\033[0m\]"       # Text Reset
CYAN="\[\033[0;36m\]"		# Cyan
GREEN="\[\033[0;32m\]"		# Green
BROWN="\[\033[0;33m\]"		# Brown

export PS1="$CYAN\u$COLOROFF@$GREEN\h:$BROWN\w$COLOROFF$CYAN\$(parse_git_branch)$COLOROFF \$ "
export CLICOLOR=1
export LSCOLORS=ExFxBxDxCxegedabagacad