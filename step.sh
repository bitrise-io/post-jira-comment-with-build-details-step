#!/bin/bash

THIS_SCRIPTDIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

source "${THIS_SCRIPTDIR}/_bash_utils/utils.sh"
source "${THIS_SCRIPTDIR}/_bash_utils/formatted_output.sh"

# init / cleanup the formatted output
echo "" > "${formatted_output_file_path}"

if [ -z "${git_branch}" ] ; then
	write_section_to_formatted_output "# Error"
	write_section_start_to_formatted_output '* Required input `$git_branch` not provided!'
	exit 1
fi

if [ -z "${jira_user}" ] ; then
	write_section_to_formatted_output "# Error"
	write_section_start_to_formatted_output '* Required input `$jira_user` not provided!'
	exit 1
fi

if [ -z "${jira_password}" ] ; then
	write_section_to_formatted_output "# Error"
	write_section_start_to_formatted_output '* Required input `$jira_password` not provided!'
	exit 1
fi

if [ -z "${jira_build_message}" ] ; then
	write_section_to_formatted_output "# Error"
	write_section_start_to_formatted_output '* Required input `$jira_build_message` not provided!'
	exit 1
fi

if [ -z "${jira_url}" ] ; then
	write_section_to_formatted_output "# Error"
	write_section_start_to_formatted_output '* Required input `$jira_url` not provided!'
	exit 1
fi

if [ -z "${fail_missing_key}" ] ; then
	write_section_to_formatted_output "# Error"
	write_section_start_to_formatted_output '* Required input `$fail_missing_key` not provided!'
	exit 1
fi

resp=$(php "${THIS_SCRIPTDIR}/application.php")
ex_code=$?

if [ ${ex_code} -eq 0 ] ; then
	echo "${resp}"
	write_section_to_formatted_output "# Success"
	echo_string_to_formatted_output "Message successfully sent."
	exit 0
fi

write_section_to_formatted_output "# Error"
write_section_to_formatted_output "Sending the message failed with the following error:"
echo_string_to_formatted_output "${resp}"
exit 1
