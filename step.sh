#!/bin/bash

THIS_SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
# load bash utils
source "${THIS_SCRIPT_DIR}/bash_utils/utils.sh"
source "${THIS_SCRIPT_DIR}/bash_utils/formatted_output.sh"

function send_message() {
	# Prepare the request
	content_type="Content-Type:application/json"
	payload="'{\"body\": \"$jira_build_message\"}'"
	curl_command="eval curl -s -o /dev/null -w \"%{http_code}\" -u $jira_user:$jira_password -X POST --data $payload -H $content_type \"https://$jira_domain/rest/api/2/issue/$JIRA_ISSUE_KEY/comment\""

	# Execute the request
	write_section_to_formatted_output "=> Executing curl request"
	response=`$curl_command`
	write_section_to_formatted_output "Reponse code is $response"
	
	# Check the response
	if [ "$response" != 201 ]
	then
		write_section_to_formatted_output "The reponse code is not valid";
		exit 1;
	fi
}

regular_expression="(feature|hotfix)/([a-zA-Z]+\-[0-9]+)\-?(.*)"
if [[ $git_branch =~ $regular_expression ]]; 
then
	JIRA_ISSUE_KEY=${BASH_REMATCH[2]};
	export JIRA_ISSUE_KEY;
	
	JIRA_ISSUE_NAME=${BASH_REMATCH[3]};	
	export JIRA_ISSUE_NAME;
	
	send_message
else
	write_section_to_formatted_output "Invalid branch name : $git_branch";
	exit 1;
fi
