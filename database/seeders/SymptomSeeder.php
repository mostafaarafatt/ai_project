<?php

namespace Database\Seeders;

use App\Models\Symptom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SymptomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $symptoms = [
            "feeling_nervous", "panic", "breathing_rapidly", "sweating", "trouble_in_concentration", "having_trouble_in_sleeping", "having_trouble_with_work", "hopelessness", "anger", "over_react", "change_in_eating", "suicidal_thought", "feeling_tired", "close_friend", "social_media_addiction", "weight_gain", "introvert", "having_nightmares", "avoids_people_or_activities", "feeling_negative", "blamming_yourself", "increasd_energy", "repetitive_behavior", "hallucinations", "fear_of_contamination_or_dirt", "phantom_physical_diseases", "lack_of_awareness_of_the_surrounding_things", "memory_Loss", "feeling_irritable", "feeling_guilty__about_eating", "weight_loss", "high_ego_and_self_love", "being_in_crowded_spaces", "fear_of_separation", "speech_difficulties", "the_desire_for_revenge", "very_suspicious", "fear_of_Flying", "fear_of_Heights", "fear_of_Spiders", "panicked_when_confronted_with_bacteria", "feelings_of_dread_at_the_thought_of_pregnancy_and_birth_", "excessive_worry_about_cleanliness", "fainting_when_seeing_a_snake", "stuttering_when_looking_at_people", "fainting_when_seeing_animals", "feeling_of_Drowning", "the_desire_of_the_murder", "the_amounts_in_a_Reaction", "screaming_screams_surprise", "gale_of_shudder_and_crying", "loss_of_the_ability_to_focus", "disorder_after_the_loss_of_the_family", "loss_of_the_ability_to_walk_and_eat", "fear_of_relationships_with_others", "stealing_from_family_members", "_friends", "_or_stores_", "fluttering_the_eyelids", "blunting_of_emotional_responses_and_response_to_external_stimuli", "change_in_activity_or_mood_very_quickly", "you_miss_appointments_or_social_events_", "forgetting_the_names_and_faces_of_people_they_met", "hard_to_deal_with_people", "disorientation_with_time_and_space", "hopelessness_about_the_future", "loss_of_interest_in_daily_activities_", "difficulty_bonding_with_your_baby", "persistent_low_mood", "increased_sensitivity_to_criticism_or_rejection", "problems_with_decision_making", "feeling_exploited_by_others", "chokig", "afraid_of_closed_spaces", "fear_when_seeing_dogs", "fear_of_women", "fear_of_corpses", "fear_of_driving_and_riding_cars", "fear_of_stairs_and_slopes", "avoid_visiting_the_dentist", "fear_of_places_where_clowns_are_present", "fear_of_pointed_things"
        ];

        foreach (array_unique($symptoms) as $symptom) {
//            $symptom = ucwords(str_replace("_", " ", $symptom));
            Symptom::create(['name' => $symptom]);
        }
    }
}
