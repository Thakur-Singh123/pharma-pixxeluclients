$(document).ready(function() {
    function fetchVisits(page = 1, search = '') {
        $.ajax({
            url: base_url + '/mr/visits',
            type: 'GET',
            data: { page: page, search: search },
            success: function(data) {
                $('#visitResults').html($(data).find('#visitResults').html());
            }
        });
    }
    //keyup search
    $('#visitSearch').on('keyup', function() {
        let query = $(this).val().trim();
        if(query.length === 0) {
            fetchVisits(1, '');
            history.pushState(null, null, base_url + '/mr/visits');
            return;
        }
        if(query.length < 2) return;
        fetchVisits(1, query);
        let newUrl = base_url + '/mr/visits?search=' + encodeURIComponent(query);
        history.pushState(null, null, newUrl);
    });
    //Pagination links click
    $(document).on('click', '#visitResults .pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        let page = url.split('page=')[1];
        let query = $('#visitSearch').val();
        fetchVisits(page, query);
        let newUrl = base_url + '/mr/visits?page=' + page;
        if(query) newUrl += "&search=" + encodeURIComponent(query);
        history.pushState(null, null, newUrl);
    });
    window.onpopstate = function(event) {
        let params = new URLSearchParams(window.location.search);
        let page = params.get('page') || 1;
        let search = params.get('search') || '';
        $('#visitSearch').val(search);
        fetchVisits(page, search);
    };
});
