<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Cmgmyr\Messenger\Models\Thread;
use Cmgmyr\Messenger\Models\Participant;

use Carbon\Carbon;

use Game\User;
use Game\Model\Match;
use Game\Model\Region;
use Game\Model\Continent;

use Illuminate\Support\Facades\Hash;

class TestMatchSeeder extends Seeder {
    
    public function run() {
        
        DB::table('matches')->delete();
        DB::table('regions')->delete();
        DB::table('region_neighborregion')->delete();
        DB::table('continents')->delete();
        
        $player1 = User::find(1);
        $player2 = User::find(2);
        
        //$player1->newtroops = 5;
        $player1->save();
        
        $thread = $this->createThread($player1, $player2);
        
        $roundphasedata = new stdClass();
        /*$roundphasedata->base = 3;
        $roundphasedata->regions = 2;
        $roundphasedata->america = 0;*/
        $roundphasedata->conqueredregions = 2;
        $json = json_encode($roundphasedata);
        
        $match = Match::create([
            'name' => 'Test Match',
            'state' => 'started',
            'roundphase' => 'attack',
            'roundphasedata' => $json,
            'active_player_id' => $player1->id,
            'public' => false,
            'created_by_user_id' => $player1->id,
            'thread_id' => $thread->id,
            'joinid' => '1406c52af762d6e34250d36ba4011aab',
            'mapname' => 'earth',
            'maxusers' => 2,
            'cardchangebonuslevel' => 0,
        ]);
        
        $player1->joinedMatch()->associate($match);
        $player1->matchcolor = 'red';
        $player1->matchorder = 1;
        $player1->save();
        
        $player2->joinedMatch()->associate($match);
        $player2->matchcolor = 'blue';
        $player2->matchorder = 2;
        $player2->save();
        
        $america = Continent::create([
            'name' => 'america',
            'colorscheme' => 'green',
            'troopbonus' => 2,
            'match_id' => $match->id,
            'owner_id' => $player1->id,
        ]);
        $southamerica = Continent::create([
            'name' => 'southamerica',
            'colorscheme' => 'red',
            'troopbonus' => 3,
            'match_id' => $match->id,
            'owner_id' => $player2->id,
        ]);
        
        $canada = Region::create([
            'name' => 'canada',
            'troops' => 12,
            'continent_id' => $america->id,
            'owner_id' => $player1->id,
            'cardunittype' => '1',
            //'card_owner_id' => $player1->id,
            'svgdata' => 'M212.989,24.93l-1.416,1.159l-3.862-0.257l-3.347-0.644l1.417-1.288l3.99-0.772l2.317,1.03l-0.901-0.772L212.989,24.93zM212.474,18.107l-1.287,0.13l-5.02-0.13l-0.772-0.772h5.535l1.802,0.515l0.258-0.257L212.474,18.107zM204.622,14.761l3.218,0.901l-0.772,1.03l-3.991,0.515l-2.188-0.644l-1.159-0.901l-0.257-1.159l3.604,0.129l-1.545-0.129L204.622,14.761zM227.793,26.604l-4.377-0.387l-7.208-0.9l-0.901-1.417l-0.258-1.287l-2.703-1.287l-5.664-0.257l-3.09-0.901l1.03-1.031l5.535,0.13l2.962,0.901h5.406l2.317,0.901l-0.643,1.029l3.089,0.515l1.673,0.643l3.605,0.13l3.99,0.257L236.804,23l5.535-0.129L246.716,23l2.832,1.029l0.644,1.159l-1.674,0.644l-3.991,0.644l-3.475-0.387l-7.724,0.387l5.535-0.128L227.793,26.604zM165.489,16.434l3.862,0.386l-0.902,0.901l-5.02,0.772l-3.991-0.9l2.188-0.901L165.489,16.434zM166.261,14.632l3.604,0.644l-3.347,0.515h-4.505l0.128-0.387l2.704-0.901L166.261,14.632zM205.137,40.636l2.703,1.158l-1.673,0.902l-3.605-1.031l-2.188,0.516l-3.09-0.387l1.803-1.673l1.931-1.159l2.059,0.643l-2.06-1.031L205.137,40.636zM315.458,88.781l-1.417,1.673l-1.802,2.317l1.802-0.9l1.802,0.643l-1.029,0.902l2.446,0.772l1.287-0.772l2.574,0.901l-0.772,1.93l1.932-0.386l0.257,1.417l0.9,1.673l-1.157,2.317l-1.288,0.129l-1.673-0.515l0.515-2.189l-0.771-0.386l-3.09,2.317h-1.545l1.801-1.287l-2.573-0.644l-2.832,0.13l-5.278-0.13l-0.386-0.772l1.674-0.901l-1.159-0.773l2.317-1.673l2.702-4.248l1.675-1.545l2.316-0.901l1.288,0.129l0.516-0.772L315.458,88.781zM239.25,51.578l2.96,0.901l3.09,0.901l0.258,1.287l1.93-0.257l1.931,0.9l-2.316,0.903l-4.249-0.774l-1.544-1.158l-2.575,1.416l-3.861,1.416l-0.902-1.544l-3.733,0.257l2.317-1.416l0.386-2.06l0.901-2.445l1.931,0.257l0.515,1.158l1.417-0.514l-1.544-0.772L239.25,51.578zM218.525,6.393l7.08-0.643l5.278-0.386l5.921-0.13l3.604-1.415l11.199-0.773l9.656,0.129l7.723-0.386l18.924,0.514l10.555,1.802L291.9,6.264l-6.437,0.515l-2.445,0.644h5.792L278.126,9.74l-10.169,2.704l-9.913,0.9l3.734,0.258l-1.931,0.515l2.317,1.287l-6.694,1.674l-1.287,1.159l-3.863,0.772l0.387,0.643l3.604,0.258v0.644l-6.049,1.158l-7.081-0.643l-7.981,0.386l-9.012-0.515l-0.385-1.288l5.02-0.643l-1.158-0.902l2.187-0.9l6.437,0.9l-7.981-2.316l2.188-1.03l4.763-0.644l0.773-0.901l-3.862-1.03l-1.159-1.416l7.338,0.129l6.437-0.644l-15.577-0.128l-4.762-1.031l-5.407-1.802l0.515,0.901L218.525,6.393zM253.024,32.01l2.574-1.03l5.922,1.417l3.734,1.287l0.385,1.158l5.02-0.643l2.833,1.674l6.437,1.158l2.317,1.03l2.574,2.575l-4.891,1.158l6.307,1.803l4.248,0.643l3.862,2.446l4.248,0.128l-0.773,1.932l-4.763,3.089l-3.347-1.158l-4.248-2.575l-3.476,0.386l-0.257,1.545l2.832,1.545l3.605,1.287l1.159,0.644l1.673,2.704l-0.902,1.93l-3.347-0.772l-6.821-2.061l3.862,2.318l2.702,1.545l0.516,1.03l-7.339-1.159l-5.793-1.545l-3.218-1.286l0.903-0.774l-3.991-1.415l-3.992-1.287l0.129,0.772l-7.853,0.386l-2.188-0.901l1.675-1.931l5.149-0.129l5.535-0.257l-0.901-1.031l0.901-1.287l3.475-2.702l-0.772-1.159l-1.03-0.901l-4.12-1.288l-5.406-0.901l1.674-0.772l-2.832-1.674l-2.317-0.129l-2.189-0.9l-1.416,0.772l-4.891,0.385l-9.784-0.643l-5.664-0.772l-4.377-0.386l-2.317-0.901l2.832-1.287h-3.862l-0.772-2.704l2.059-2.446l2.704-1.03l6.951-0.772l-1.931,1.802l2.188,1.674l2.447-2.189l6.823-1.159l4.633,2.832l-0.386,1.675l-5.278,0.774L253.024,32.01zM210.672,27.248l5.536,0.128l5.148,0.645l-3.989,2.445l-3.219,0.514l-2.833,1.932l-3.088-0.128l-1.675-2.318v-1.287l1.417-1.158L210.672,27.248zM206.552,9.869l1.931-0.901l2.704-0.128l-1.159-0.644l6.308-0.129l3.348,1.416l8.753,1.673l5.664,2.06l-3.733,0.772l-5.021,2.06l-4.763,0.258l-5.535-0.386l-2.961-1.031l0.129-1.03l2.059-0.772l-4.891,0.129l-2.961-0.902l-1.673-1.287L206.552,9.869zM194.71,31.109l-2.832-2.574l2.961-0.514l3.218,0.643l4.119-0.258l0.515,1.03l-1.544,0.901l3.604,1.803l-0.644,1.416l-3.862,1.415l-2.574-0.257l-1.803-1.03l-5.535-1.544l-1.673-1.16L194.71,31.109zM178.233,30.08l3.089,1.158l1.674,2.574l0.772,1.932l4.634,1.287l4.764,1.287l-0.258,1.159l-4.377,0.257l1.673,1.03l-0.9,1.03h-6.436l-1.804-0.644l-4.376-0.386l-5.278,1.545l-6.565,0.644l-3.604,0.128l-2.704-2.059l-6.05-0.386l-4.505-1.674l2.96-0.772l4.119-0.386l3.863,0.129l3.475-0.516l-5.149-0.644l-5.793,0.258l-3.862-0.129l-1.416-0.901l6.308-1.159l-4.249,0.129l-4.634-0.772l2.189-2.059l1.932-1.031l7.208-1.673l2.703,0.515l-1.287,1.287l5.922-0.772l3.861,1.287l2.961-1.287l2.446,0.901l2.189,2.574l1.416-1.157l-1.932-2.704l2.446-0.387L178.233,30.08zM174.757,22.613l2.446-0.385l2.832,0.128l0.385,1.287l-1.543,1.287l-9.141,0.387l-6.822,1.159l-4.12,0.128l-0.257-0.901l5.535-1.159l-12.228,0.257l-3.734-0.514l3.734-2.575l2.445-0.772l7.596,0.901l4.891,1.673l4.634,0.129l-3.862-2.574l2.446-1.03l1.803,0.643l0.9,1.287l-2.06-0.644L174.757,22.613zM134.336,21.969l4.506-2.059l5.535-1.803l4.12,0.13l3.732-0.387l-0.385,2.06l-2.06,0.901l-2.575,0.129l-5.02,1.158l-4.248,0.386l3.605,0.515L134.336,21.969zM137.812,26.476l3.862,0.514l6.823,0.129l2.703,0.772l2.832,1.158l-3.347,0.644l-6.694,1.674L140,33.427l-0.643,1.287l-5.664,1.287l-1.802-1.03l-5.922-1.544l0.129-0.902l2.188-2.317l2.06-1.159l-1.673-2.188L137.812,26.476zM107.69,81.443l2.574-0.256l-0.773,3.088l2.318,2.188h-1.03l-1.674-1.287l-0.9-1.287l-1.416-0.772l-0.516-1.158l0.13-0.902l-1.287-0.386L107.69,81.443zM199.73,20.682l1.288,0.901V23l-1.416,1.801l-3.218,0.387l-2.961-0.387l0.129-1.545l-4.507,0.13l-0.128-2.06l2.961,0.129l3.99-0.901l-3.862-0.128L199.73,20.682zM181.064,13.344l5.279,0.387l7.337,0.901l2.06,1.288l1.03,1.158l-4.377-0.258l-4.506-0.9l-5.922-0.129l2.576-0.773l-3.348-0.644l0.129,1.03L181.064,13.344zM127.385,92.386l1.288,1.287l2.702,1.158l1.16,1.416l-1.417,0.387l-4.376-1.159l-0.773-1.029l-2.446-0.903l-0.515-0.772l-2.703-0.514l-1.03-1.416l0.129-0.643l2.832,0.643l1.673,0.386l2.575,0.257l-0.901-0.902L127.385,92.386zM315.071,83.502l0.129,2.961l-1.932,1.031l-1.932,0.901l-4.376,1.03l-3.476,2.188l-4.505,0.386l-5.793-0.515h-3.99l-2.832,0.129l-2.318,1.93l-3.346,1.288l-3.863,3.476l-3.089,2.575l2.189-0.515l4.376-3.476l5.664-2.317l3.991-0.257l2.445,1.286l-2.573,1.932l0.772,2.832l0.901,2.06l3.476,1.287l4.504-0.387l2.704-2.96l0.258,1.931l1.673,1.029l-3.347,1.674l-5.921,1.674l-2.703,1.029l-2.961,1.931l-2.06-0.128l-0.128-2.317l4.633-2.189h-4.247l-2.961,0.387l-1.803-1.545v-3.605l-1.157-0.772l-1.804,0.386l-0.9-0.644l-2.06,1.932l-0.901,2.187l-0.902,1.159l-1.158,0.515h-0.901l-0.258,0.772h-4.891h-4.12l-1.287,0.516l-2.703,1.801l-0.387,0.258l-0.256,0.258l-0.387,0.386l-0.257,0.515h-0.643h-0.516h-0.901l-0.772-0.128h-0.902h-0.643l-0.772,0.128h-0.258l-0.515,0.257l-0.386,0.129l0.257,0.386v0.129l0.387,0.772v0.258v0.128l-0.258,0.13l-0.386,0.128l-0.772,0.258l-0.902,0.257l-0.643,0.257l-0.643,0.258l-0.644,0.129h-0.128h-0.387l-0.9,0.128l-0.645,0.129l-0.644,0.258l-0.643,0.385l-0.644,0.258l-0.644,0.257l-0.643,0.258h-0.644l-0.514-0.129l-0.387-0.257l-0.257-0.257v-0.13v-0.257l0.644-0.9l1.286-1.546v-0.128v-0.129l0.259-0.515l0.385-0.515l0.129-0.258l-0.258-0.771l-0.129-0.515v-0.386l-0.127-0.515l-0.13-0.515l-0.129-0.515l-0.128-0.386l-0.13-0.515v-0.257l-0.128-0.387l-0.515-0.386l-0.514-0.128l-0.644-0.258l-0.643-0.257l-0.516-0.257l0.386-0.515v-0.129h-0.128l-0.258-0.258h-0.128l-0.258,0.128l-0.386-0.128l-0.258-0.129h-0.128l-0.129-0.257h-0.129v-0.258v-0.128v-0.129v-0.129h-0.257l-0.258,0.258h-0.772l0.128-0.258h-0.257l-0.386-0.257l-0.128-0.387l-0.13-0.386l-0.514-0.257l-0.515-0.129l-0.515-0.258l-0.515-0.257l-0.515-0.128l-0.515-0.258l-0.515-0.258l-0.514-0.128l-0.258-0.128l-0.387-0.13l-0.643-0.257l-0.772-0.386l-0.772-0.258l-0.773-0.257l-0.386-0.257h-0.258l-0.386-0.258l-0.644-0.129l-0.643,0.129l-0.772,0.258l-0.387,0.128l-0.386,0.129l-0.258,0.129h-0.515h-0.385l-3.219-0.773l-2.188,0.387l-2.703-0.773l-2.704-0.515l-1.93-0.129l-0.772-0.514l-0.516-1.417h-0.901v1.03h-5.536h-9.139h-9.397h-32.182h-2.704H133.95l-5.149-2.574l-1.931-1.287l-4.891-1.03l-1.545-2.446l0.385-1.673l-3.474-1.031l-0.387-2.188l-3.348-2.061v-1.287l1.417-1.287v-1.802l-4.634-1.673l-2.703-3.09l-1.674-1.93l-2.446-1.159l-1.802-1.159l-1.545-1.417l-2.703,0.902l-2.575,1.545L92.5,66.51l-1.802-1.157l-2.704-0.774H85.42V49.133V39.22l5.019,0.644l4.249,1.286l2.832,0.258l2.317-1.158l3.347-0.901l3.99,0.385l3.992-1.157l4.376-0.644l1.931,1.029l1.931-0.644l0.643-1.158l1.803,0.257l4.634,2.447l3.604-1.931l0.387,2.059l3.218-0.387l1.029-0.772l3.219,0.129l4.12,1.159l6.307,0.901l3.733,0.515l2.704-0.129l3.604,1.288l-3.734,1.415l4.763,0.515l7.338-0.257l2.317-0.515l2.832,1.544l2.96-1.287l-2.832-1.158l1.803-0.901l3.218-0.129l2.189-0.258l2.188,0.644l2.703,1.417l2.961-0.258l4.763,1.287l4.248-0.386h3.862l-0.258-1.673l2.446-0.515l4.12,0.9v2.576l1.673-2.06h2.188l1.288-2.704l-2.962-1.673l-3.088-1.03l0.128-2.961l3.218-2.06l3.605,0.515l2.703,1.158l3.604,3.091l-2.317,1.287l5.02,0.514v2.832l3.605-2.189l3.218,1.804l-0.9,1.93l2.702,1.802l2.704-1.931l2.06-2.317l0.129-2.96l3.861,0.257l3.862,0.387l3.733,1.287l0.128,1.416l-2.059,1.416l1.931,1.416l-0.386,1.286l-5.277,1.932l-3.734,0.386l-2.704-0.772l-0.901,1.287l-2.574,2.317l-0.773,1.159l-3.089,1.802l-3.862,0.257l-2.188,1.031l-0.13,1.802l-3.089,0.386l-3.347,2.188l-2.961,2.961l-1.028,2.188l-0.13,3.09l3.991,0.386l1.159,2.576l1.287,2.059l3.733-0.515l5.02,1.159l2.704,1.029l1.93,1.288l3.347,0.643l2.832,1.158l4.507,0.129l2.959,0.258l-0.514,2.446l0.901,2.702l1.931,2.961l3.991,2.576l2.059-0.902l1.545-2.703l-1.416-4.247l-1.931-1.545l4.247-1.159l3.09-1.931l1.545-1.931l-0.257-1.803l-1.802-2.188l-3.348-2.06l3.219-2.832l-1.158-2.445l-0.902-4.249l1.931-0.514l4.506,0.643l2.832,0.257l2.188-0.644l2.575,0.902l3.347,1.545l0.772,1.029l4.763,0.259v2.187l0.901,3.476l2.446,0.386l1.931,1.545l3.862-1.416l2.574-2.961l1.802-1.287l2.06,2.446l3.605,3.347l2.96,3.218l-1.159,1.802l3.604,1.417l2.446,1.545l4.25,0.772l1.802,0.772l1.03,2.317l2.06,0.387l-1.158-1.028L315.071,83.502z',
            'centerx' => 177,
            'centery' => 64,
            'labelcenterx' => 167,
            'labelcentery' => 54,
            'angle' => -2,
        ]);
        
        $usa = Region::create([
            'name' => 'usa',
            'troops' => 32,
            'continent_id' => $america->id,
            'owner_id' => $player1->id,
            'cardunittype' => '2',
            //'card_owner_id' => $player1->id,
            'svgdata' => 'M284.434,106.546l-2.704,0.772l-2.575,0.644l-3.089,1.673l-1.287,1.417l-0.258,0.386l-0.127,1.545l0.9,1.415h1.159l-0.259-0.9l0.773,0.515l-0.257,0.772l-1.803,0.515l-1.286-0.13l-1.931,0.517l-1.159,0.128l-1.673,0.128l-2.06,0.644l3.733-0.387h0.128l0.773,0.515l-3.733,0.773h-1.802l0.129-0.257l0.128-0.644l-0.9,1.416h0.643l-0.515,2.06l-1.931,2.06l-0.257-0.773l-0.516-0.129l-0.643-0.643h-0.129h-0.128l0.514,1.416l0.773,1.416l0.129,0.257l-1.03,0.901l-1.545,2.188l-0.258-0.128l1.03-1.802l-1.416-1.287l-0.128-2.06l-0.387,0.901v2.446l-1.673-0.901l1.802,1.544l0.515,1.417l0.772,1.674l0.387,2.703l-1.803,1.93l-2.574,1.03l-2.318,1.417l-0.901,0.128l-1.158,1.931l-2.317,1.673l-2.832,1.288l-1.158,2.06l-0.516,1.415l0.387,2.061l1.03,2.187l1.159,2.061v1.029l1.157,2.703l0.129,2.447l-0.514,2.316l-1.159,0.516l-1.287-0.386l-0.386-1.159l-1.031-0.644l-1.545-2.317l-1.287-1.931l-0.257-1.287l0.515-1.674l-0.643-1.544l-1.803-1.545l-1.416-1.03l-3.089,1.158l-0.644-0.772l-2.574-1.287l-2.962,0.386l-2.445-0.258l-1.674,0.515h-1.544l-0.258,1.16l0.772,1.543l-3.605,0.13l-2.316-0.516l-1.545-0.514l-2.059-0.387l-2.318-0.128l-2.317,0.643l-2.446,1.931l-2.702,1.158l-1.417,1.289l-0.644,1.287v1.802l0.129,1.287l0.515,0.901l-1.03,0.129l-1.931-0.643l-2.188-0.773l-0.772-1.287l-0.515-1.931l-1.545-1.545l-1.03-1.545l-1.288-1.802l-1.93-1.159l-2.189,0.13l-1.674,2.058l-2.316-0.772l-1.288-0.772l-0.772-1.545l-0.9-1.416l-1.545-1.159l-1.416-0.901l-0.902-0.9h-4.633l-0.129,1.158h-2.06h-5.407l-6.178-1.931l-3.992-1.288l0.258-0.515l-3.475,0.259l-3.09,0.256l-0.258-1.029l-1.159-1.416l-2.831-1.545l-1.158-0.129l-1.16-0.9l-2.059-0.13l-0.772-0.515L140,132.292l-2.702-2.704l-2.189-3.732l0.128-0.644l-1.287-0.901l-2.059-2.317l-0.386-2.188l-1.417-1.417l0.644-2.189l-0.129-2.317l-0.901-1.544l0.901-2.96l0.129-2.962l0.514-4.119l-0.771-2.188l-0.387-2.575l3.734,0.515l1.158,2.06l0.644-0.773l-0.387-2.188l-1.287-2.189h15.962h2.704h32.182h18.536h5.536v-1.03h0.901l0.516,1.417l0.772,0.514l1.93,0.129l2.704,0.515l2.703,0.773l2.188-0.387l3.219,0.773h0.385h0.515l0.258-0.129l0.386-0.129l0.387-0.128l0.772-0.258l0.643-0.129l0.644,0.129l0.386,0.258h0.258l0.386,0.257l0.773,0.257l0.772,0.258l0.772,0.386l0.643,0.257l0.387,0.13l0.258,0.128l0.514,0.128l0.515,0.258l0.515,0.258l0.515,0.128l0.515,0.257l0.515,0.258l0.515,0.129l0.514,0.257l0.13,0.386l0.128,0.387l0.386,0.257h0.257h0.902h0.257v0.129v0.129v0.128v0.258h0.129l0.129,0.257h0.128l0.258,0.129l0.386,0.128l0.258-0.128h0.128l0.258,0.258h0.128v0.129l-0.386,0.515l0.516,0.257l0.643,0.257l0.644,0.258l0.514,0.128l0.515,0.386l0.128,0.387v0.257l0.13,0.515l0.128,0.386l0.129,0.515l0.13,0.515l0.127,0.515v0.386l0.129,0.515l0.258,0.772l-0.129,0.258l-0.385,0.515l-0.259,0.515v0.129v0.128l-0.514,0.516l-0.772,1.03l-0.387,0.385l-0.257,0.515v0.257v0.13l0.257,0.257l0.387,0.257l0.514,0.129h0.644l0.643-0.258l0.644-0.257l0.644-0.258l0.643-0.385l0.644-0.258l0.645-0.129l0.9-0.128h0.387h0.128l0.644-0.129l0.643-0.258l0.643-0.257l0.902-0.257l0.772-0.258l0.386-0.128l0.258-0.13v-0.128v-0.258l-0.387-0.772v-0.129l-0.257-0.386l0.386-0.129l0.515-0.257h0.258l0.772-0.128h0.643h0.902l0.772,0.128h0.901h0.516h0.643l0.257-0.515l0.387-0.386l0.256-0.258l0.387-0.258l2.703-1.801l1.287-0.516h4.12h4.891l0.258-0.772h0.901l1.158-0.515l0.902-1.159l0.901-2.187l2.06-1.932l0.9,0.644l1.804-0.386l1.157,0.772v3.605l1.803,1.545v-1.158V106.546zM16.808,64.322l2.059,0.257l0.258,1.031l-1.545,0.386l-1.802-0.516l-1.673-0.772l-2.703,0.386L16.808,64.322zM52.465,70.759l1.803,0.257l1.157,0.774l-2.317,1.286l-2.703,1.029l-1.416-0.643l-0.385-1.288l2.445-0.901l-1.416,0.514L52.465,70.759zM85.42,39.22v9.913v15.446h2.574l2.704,0.774L92.5,66.51l2.445,1.803l2.575-1.545l2.703-0.902l1.545,1.417l1.802,1.159l2.446,1.159l1.674,1.93l2.703,3.09l4.634,1.673v1.802l-1.417,1.287l-1.544-1.029l-2.316-0.901l-0.773-2.318l-3.476-2.189l-1.415-2.573l-2.576-0.129l-4.376-0.129l-3.09-0.773l-5.535-2.703l-2.702-0.514l-4.636-1.031l-3.733,0.259l-5.278-1.288l-3.217-1.159l-2.962,0.643l0.515,1.804l-1.544,0.257l-3.09,0.515l-2.318,0.9l-2.961,0.644l-0.385-1.673l1.159-2.575l2.831-0.9l-0.771-0.645l-3.347,1.545l-1.802,1.802l-3.991,1.931l2.059,1.288l-2.574,1.931l-2.961,1.03l-2.704,0.9l-0.643,1.159l-4.119,1.416l-0.901,1.288l-3.09,1.158l-1.931-0.258l-2.445,0.773l-2.832,0.901l-2.189,0.902l-4.634,0.772l-0.387-0.516l2.962-1.158l2.574-0.901l2.832-1.417l3.347-0.385l1.416-1.03l3.734-1.673l0.514-0.516l2.059-0.901l0.386-2.059l1.418-1.545l-3.091,0.773l-0.901-0.516l-1.415,1.031l-1.803-1.417l-0.644,1.03l-1.029-1.417l-2.704,1.16h-1.673l-0.257-1.674l0.514-0.901l-1.673-1.029l-3.604,0.513l-2.189-1.287l-1.931-0.643v-1.545l-2.059-1.03l1.029-1.673l2.188-1.416l1.03-1.416l2.189-0.129l1.802,0.386l2.189-1.287l1.93,0.257l2.059-0.901l-0.513-1.158l-1.546-0.515l2.059-1.03h-1.673l-2.832,0.515l-0.772,0.643l-2.188-0.514l-3.863,0.257l-3.861-0.643l-1.158-1.159l-3.476-1.545l3.862-1.03l6.05-1.416h2.188l-0.386,1.416l5.665-0.129l-2.189-1.673l-3.347-1.031l-1.931-1.286l-2.574-1.158l-3.605-0.901l1.417-1.417l4.762-0.129l3.475-1.158l0.644-1.288l2.703-1.287l2.704-0.386l5.021-1.159l2.574,0.128l4.119-1.415l3.99,0.643l2.06,1.159l1.159-0.515l4.505,0.128l-0.128,0.644l4.119,0.516l2.703-0.258l5.664,0.773l5.278,0.257l2.06,0.386l3.604-0.514l3.991,0.9l-2.961-0.387L85.42,39.22zM2.647,55.182l1.673,0.515l1.674-0.258l2.189,0.644l2.574,0.386l-0.128,0.258l-2.061,0.644l-2.059-0.644l-1.03-0.514l-2.446,0.128L2.39,56.213l-0.257,1.031L2.647,55.182zM45.256,175.546v-0.773l-0.385-1.029l0.643-0.643l-0.258-0.516l0.129-0.128v-0.129l1.803,0.773l0.256,0.385v0.258l0.258,0.129l0.129,0.128l0.385,0.387l-0.643,0.514l-0.772,0.129l-0.515,0.515l-0.258,0.387L45.256,175.546zM43.067,170.01l-0.385,0.258l-1.158-0.128l0.128-0.387L43.067,170.01zM44.999,170.912v0.257l-0.258,0.129l-0.9,0.128l-0.13-0.514h-0.386l-0.258-0.387l0.13-0.128l0.257-0.129l0.257,0.385l0.516-0.128L44.999,170.912zM39.335,169.496l-0.515-0.643l0.386-0.13l0.515-0.257l0.386,0.643h0.257l0.258,0.516h-0.515l-0.257-0.129h-0.129H39.335zM34.829,167.564l0.129-0.256l0.386-0.259l0.643,0.13l0.13,0.129l-0.13,0.514l-0.256,0.258l-0.516-0.129L34.829,167.564z',
            'centerx' => 191,
            'centery' => 116,
            'labelcenterx' => 175,
            'labelcentery' => 110,
            'angle' => 2,
        ]);
        
        $mexico = Region::create([
            'name' => 'mexico',
            'troops' => 31,
            'continent_id' => $america->id,
            'owner_id' => $player1->id,
            'cardunittype' => '3',
            'card_owner_id' => $player2->id,
            'svgdata' => 'M203.592,157.266l-1.030,2.446l-0.515,1.931l-0.257,3.605l-0.257,1.287l0.514,1.416l0.773,1.287l0.644,2.188l1.802,1.931l0.515,1.545l1.158,1.416l2.832,0.643l1.029,1.159l2.447,-0.772l2.060,-0.258l1.930,-0.513l1.803,-0.388l1.672,-1.158l0.644,-1.545l0.258,-2.317l0.386,-0.772l1.803,-0.644l2.961,-0.644l2.316,0l1.674,-0.129l0.644,0.516l-0.129,1.287l-1.417,1.674l-0.643,1.544l0.515,0.515l-0.386,1.158l-0.772,2.060l-0.644,-0.644l-0.515,0l-0.515,0.130l-1.030,1.544l-0.515,-0.258l-0.257,0.129l0,0.387l-2.446,0l-2.575,0l0,1.416l-1.158,0l0.901,0.901l1.030,0.643l0.387,0.644l0.385,0.128l-0.128,0.903l-3.347,0l-1.416,2.188l0.385,0.514l-0.257,0.643l-0.128,0.773l-2.961,-2.832l-1.416,-0.901l-2.189,-0.772l-1.544,0.257l-2.189,1.030l-1.287,0.258l-1.930,-0.773l-2.060,-0.515l-2.446,-1.158l-2.061,-0.387l-3.088,-1.287l-2.189,-1.286l-0.644,-0.645l-1.545,-0.258l-2.702,-0.772l-1.159,-1.287l-2.961,-1.545l-1.288,-1.673l-0.644,-1.287l0.902,-0.258l-0.258,-0.772l0.644,-0.772l0,-0.902l-0.901,-1.158l-0.257,-1.159l-0.902,-1.287l-2.445,-2.704l-2.703,-2.059l-1.288,-1.674l-2.317,-1.159l-0.515,-0.643l0.386,-1.674l-1.287,-0.643l-1.673,-1.287l-0.644,-1.802l-1.416,-0.258l-1.545,-1.416l-1.287,-1.288l-0.129,-0.901l-1.416,-2.060l-1.029,-2.059l0.128,-1.030l-1.931,-1.030l-0.901,0.129l-1.544,-0.773l-0.515,1.159l0.515,1.288l0.257,1.930l0.901,1.160l1.931,1.801l0.515,0.644l0.386,0.257l0.386,0.902l0.515,0l0.515,1.673l0.773,0.644l0.643,1.030l1.673,1.415l0.902,2.446l0.772,1.159l0.773,1.287l0.128,1.416l1.287,0.129l1.030,1.158l1.029,1.288l-0.128,0.386l-1.029,1.030l-0.516,0l-0.772,-1.673l-1.673,-1.546l-1.931,-1.286l-1.416,-0.644l0.129,-1.931l-0.515,-1.545l-1.288,-0.773l-1.802,-1.287l-0.386,0.386l-0.644,-0.643l-1.673,-0.643l-1.545,-1.675l0.129,-0.128l1.158,0.128l1.030,-1.029l0,-1.159l-2.059,-1.931l-1.545,-0.772l-1.031,-1.674l-0.900,-1.802l-1.287,-2.189l-1.159,-2.317l3.090,-0.256l3.475,-0.259l-0.258,0.515l3.992,1.288l6.178,1.931l5.407,0l2.060,0l0.129,-1.158l4.633,0l0.902,0.900l1.416,0.901l1.545,1.159l0.900,1.416l0.772,1.545l1.288,0.772l2.316,0.772l1.674,-2.058l2.189,-0.130l1.930,1.159l1.288,1.802l1.030,1.545l1.545,1.545l0.515,1.931l0.772,1.287l2.188,0.773l1.931,0.643l-1.030,0.129z',
            'centerx' => 188,
            'centery' => 163,
            'labelcenterx' => 188,
            'labelcentery' => 149,
            'angle' => 20,
        ]);
        
        $brazil = Region::create([
            'name' => 'brazil',
            'troops' => 1,
            'continent_id' => $southamerica->id,
            'owner_id' => $player2->id,
            'cardunittype' => '3',
            'card_owner_id' => $player1->id,
            'svgdata' => 'M310.05,308.396l3.605-3.732l2.961-2.576l1.801-1.158l2.319-1.416v-2.188l-1.288-1.544l-1.416,0.516l0.515-1.546l0.386-1.545v-1.544l-0.9-0.516l-1.031,0.516l-1.028-0.129l-0.259-1.031l-0.256-2.443l-0.516-0.902l-1.802-0.643l-1.159,0.514l-2.831-0.514l0.128-3.736l-0.772-1.414l0.901-0.643l-0.257-1.545l0.771-1.158l0.386-2.061l-0.643-1.676l-1.416-0.771l-0.258-1.029l0.386-1.543l-5.148-0.131l-1.031-3.219h0.772l-0.128-1.158l-0.516-0.772l-0.128-1.544l-1.545-0.773h-1.673l-1.159-0.771l-1.801-0.516l-1.03-1.029l-2.962-0.516l-2.96-2.316l0.258-1.803l-0.387-1.158l0.258-1.931l-3.476,0.386l-1.416,1.029l-2.446,1.16l-0.515,0.771h-1.415l-2.06-0.127l-1.416,0.383l-1.287-0.256l0.256-4.119l-2.317,1.545h-2.317l-1.03-1.416l-1.801-0.129l0.644-1.158l-1.546-1.674l-1.158-2.445l0.772-0.516v-1.158l1.545-0.773l-0.257-1.416l0.772-0.9l0.129-1.289l3.089-1.801l2.188-0.516l0.386-0.514l2.446,0.129l1.159-7.338l0.129-1.159l-0.515-1.544l-1.159-1.03v-1.931l1.545-0.387l0.515,0.258l0.129-1.029l-1.545-0.258l-0.129-1.674h5.278l0.9-0.902l0.773,0.902l0.515,1.545l0.516-0.387l1.544,1.416l2.06-0.129l0.515-0.771l1.93-0.645l1.159-0.515l0.257-1.159l1.931-0.771l-0.128-0.514l-2.188-0.26l-0.387-1.672v-1.805l-1.158-0.643l0.514-0.257l2.06,0.257l2.059,0.773l0.774-0.643l1.93-0.516l3.09-0.902l0.9-1.029l-0.257-0.772l1.287-0.129l0.644,0.644l-0.257,1.158l0.9,0.387l0.644,1.287l-0.772,0.902l-0.515,2.316l0.773,1.287l0.128,1.287l1.674,1.287l1.288,0.129l0.386-0.516l0.771-0.128l1.288-0.515l0.901-0.645l1.416,0.26l0.643-0.131l1.546,0.131l0.258-0.518l-0.517-0.514l0.259-0.773l1.158,0.26l1.159-0.26l1.545,0.516l1.287,0.516l0.771-0.645l0.644,0.129l0.387,0.771l1.287-0.256l1.03-1.031l0.771-1.93l1.545-2.446l1.029-0.128l0.646,1.415l1.544,4.763l1.416,0.387v1.931l-1.932,2.188l0.773,0.772l4.763,0.388l0.128,2.701l2.06-1.674l3.348,0.902l4.505,1.674l1.288,1.545l-0.387,1.545l3.09-0.9l5.277,1.414h3.991l3.99,2.189l3.476,2.961l2.06,0.771l2.317,0.129l0.9,0.901l0.901,3.476l0.516,1.545l-1.159,4.504l-1.287,1.676l-3.861,3.863l-1.674,2.959l-2.06,2.316l-0.643,0.129l-0.773,1.932l0.257,5.02l-0.772,4.25l-0.256,1.672l-0.902,1.158l-0.515,3.605l-2.703,3.475l-0.388,2.833l-2.187,1.158l-0.645,1.546h-2.96l-4.249,1.027l-1.931,1.289l-2.96,0.772l-3.219,2.06l-2.188,2.703l-0.386,2.061l0.386,1.416l-0.515,2.703l-0.645,1.416l-1.803,1.416l-2.96,4.764l-2.446,2.189l-1.802,1.156l-1.287,2.574l-1.673,1.545l-0.771-1.545l1.157-1.286l-1.545-1.804l-2.188-1.414l-2.702-1.805l-1.03,0.129l-2.704-2.059L310.05,308.396z',
            'centerx' => 331,
            'centery' => 257,
            'labelcenterx' => 331,
            'labelcentery' => 243,
            'angle' => 30,
        ]);
        
        $canada->neighbors()->attach($usa);
        $usa->neighbors()->attach($canada);
        
        $usa->neighbors()->attach($mexico);
        $mexico->neighbors()->attach($usa);
        
        $mexico->neighbors()->attach($brazil);
        $brazil->neighbors()->attach($mexico);
        
        $canada->save();
        $usa->save();
        $mexico->save();
        $brazil->save();
        
    }
    
    
    protected function createThread(User $initiator, User $participant){
        
        $thread = Thread::create([
            'subject' => "Test Match Thread"
        ]);
        Participant::create([
            'thread_id' => $thread->id,
            'user_id' => $initiator->id,
            'last_read' => new Carbon
        ]);
        $thread->addParticipants([$participant->id]);
        
        return $thread;
        
    }
    
}