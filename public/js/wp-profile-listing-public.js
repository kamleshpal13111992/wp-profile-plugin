(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	 // Ajax request on search form submission

	jQuery(document).ready(function($) {
	    var page 			= 1;
	    var $content 		= $('#custom-profile-listing');
	    var $loader 		= $('#ajax-loader');
	    var maxPages 		= parseInt(custom_profile_vars.max_pages);
	    var currentOrder 	= 'asc';
	    $('.skills-filter , .education-filter').select2();

	    function load_posts() {

	    	var titleFilter 			= $('#title-filter').val();
	    	var skillsFilter 			= $('#skills-filter').val();
        	var educationFilter 		= $('#education-filter').val();
			var ratingFilter 			= $('#rating-filter').val();
			var ageRangeMin 			= $('#min-age-label').text();
			var ageRangeMax 			= $('#age-range').val();
			var ageRange 				= ageRangeMin + '-' + ageRangeMax;
	    	var jobsCompletedFilter 	= $('#jobs-completed-filter').val();
        	var yearsOfExperienceFilter = $('#years-of-experience-filter').val();

        	// Show the loader before making the Ajax request
        	$loader.show();

	        $.ajax({
	            type: 'POST',
	            url: custom_profile_vars.ajaxurl,
	            data: {
	                action: 'custom_profile_search',
	                security: custom_profile_vars.nonce,
	                page: page,
	                title_filter: titleFilter,
	                skills_filter: skillsFilter,
                	education_filter: educationFilter,
                	rating_filter: ratingFilter,
                	age_range: ageRange,
	                jobs_completed_filter: jobsCompletedFilter,
                	years_of_experience_filter: yearsOfExperienceFilter,
                	order: currentOrder,
	            },
	            success: function(response) {
	                $content.html(response);
	                $('.pagination').on('click', 'a', function(e) {
				    	e.preventDefault();
				        page = $(this).data('page');
				        load_posts();
				    });

				    // Initial setup for sorting arrows
				    $('.sort-arrow').on('click', function() {
				        currentOrder = (currentOrder === 'asc') ? 'desc' : 'asc';
				        load_posts();
				    });

				    // Hide the loader after posts are loaded
                	$loader.hide();
	            }
	        });
	    }

	    $('#search-button').on('click', function(e) {
	        e.preventDefault();
	        $('html, body').animate({
	            scrollTop: $('#custom-profile-listing').offset().top
	        }, 800);
	        page = 1;
	        load_posts();
	    });

	    $('.sort-arrow').on('click', function() {
	        currentOrder = (currentOrder === 'asc') ? 'desc' : 'asc';
	        load_posts();
	    });

	    // Update the slider based on user input
	    $('#age-range').on('input', function() {
	        $('#age-label').text($(this).val());
	    });

	    load_posts();
	});

})( jQuery );