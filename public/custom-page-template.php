<?php

/* Template Name: Custom Page Template */

get_header();

?>

<div class="main_wrapper">
	<h4>Profile List with Ajax Searching</h4>
	<div>
	    <label for="title-filter">Keyword</label>
	    <input type="text" id="title-filter" name="title-filter">
	</div>

	<div class="age_rating_wrapper">
		<div class="left_section">
		    <label for="skills-filter">Skills</label>
		    <select id="skills-filter" name="skills-filter[]" class="skills-filter" multiple="multiple">
		        <?php
		        $skills = get_terms(array('taxonomy' => 'skills', 'hide_empty' => false));
		        foreach ($skills as $skill) {
		            echo '<option value="' . esc_attr($skill->term_id) . '">' . esc_html($skill->name) . '</option>';
		        }
		        ?>
		    </select>
		</div>

		<div class="right_section">
		    <label for="education-filter">Education</label>
		    <select id="education-filter" name="education-filter[]" class="education-filter" multiple="multiple">
		        <?php
		        $educations = get_terms(array('taxonomy' => 'education', 'hide_empty' => false));
		        foreach ($educations as $education) {
		            echo '<option value="' . esc_attr($education->term_id) . '">' . esc_html($education->name) . '</option>';
		        }
		        ?>
		    </select>
		</div>
	</div>

	<div class="age_rating_wrapper">
		<div class="left_section">
			<label for="age-range">Age Range</label>
		    <span id="min-age-label">0</span>
		    <input type="range" id="age-range" name="age-range" min="0" max="99" step="1" value="99">
		    <span id="age-label">99</span>
		</div>

		<div class="right_section">
		    <label for="rating-filter">Rating</label>
		    <select id="rating-filter" name="rating-filter">
		        <option value="">All Ratings</option>
		        <?php for ($i = 1; $i <= 5; $i++) : ?>
		            <option value="<?php echo esc_attr($i); ?>"><?php echo esc_html($i); ?></option>
		        <?php endfor; ?>
		    </select>
		</div>
	</div>


	<div class="job_year_wrapper">
		<div class="left_section">
		    <label for="jobs-completed-filter">No of Jobs Completed</label>
		    <input type="number" id="jobs-completed-filter" min="1" max="100" name="jobs-completed-filter">
		</div>

		<div class="right_section">
		    <label for="years-of-experience-filter">Years of Experience</label>
		    <input type="number" id="years-of-experience-filter" min="1" max="30" name="years-of-experience-filter">
		</div>
	</div>

	<div>
	    
	</div>

	<button id="search-button">Search</button>
</div>

<div id="ajax-loader" style="display:none;">
    <!-- Add your loader HTML/CSS here -->
    Loading... Please Wait
</div>

<!-- HTML for the profile listing -->
<div id="custom-profile-listing">
    <!-- Profile listings will be displayed here -->
</div>

<?php
get_footer();
