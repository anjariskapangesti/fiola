const approvalCountUrl = document.querySelector('meta[name="approval-count-url"]').getAttribute('content');

function getApprovalCount() {
    $.ajax({
        url: approvalCountUrl,
        type: "GET",
        success: function(data) {
            $('#account_mgr_count').text(data.account_mgr_count);
            $('#account_it_count').text(data.account_it_count);
            $('#account_it_mgr_count').text(data.account_it_mgr_count);
            $('#account_execution_count').text(data.account_execution_count);

            $('#folderaccess_mgr_count').text(data.folderaccess_mgr_count);
            $('#folderaccess_it_count').text(data.folderaccess_it_count);
            $('#folderaccess_it_mgr_count').text(data.folderaccess_it_mgr_count);
            $('#folderaccess_execution_count').text(data.folderaccess_execution_count);

            $('#manager_approvals_count').text(data.manager_approvals_count);
            $('#confirms_count').text(data.confirms_count);
            $('#it_approvals_count').text(data.it_approvals_count);
            $('#it_mgr_approvals_count').text(data.it_mgr_approvals_count);
            $('#execution_count').text(data.execution_count);
        },
        error: function(xhr, status, error) {
            alert(error);
        }
    });
}