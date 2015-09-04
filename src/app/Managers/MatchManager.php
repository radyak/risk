<?php namespace Game\Managers;

use Game\Model\Match;
use Game\Exceptions\GameException;
use Game\Services\IdTokenService;
use Game\Model\Map;

use Illuminate\Support\Facades\Log;

/**
 * Description of MatchManager
 *
 * @author fvo
 */
class MatchManager {
    
    
        const MATCHSTATE_WAITING_FOR_PLAYERJOINS = "waitingforjoins";
        const MATCHSTATE_START = "started";
        
        
        const COLORSCHEMES = [
            "red",
            "blue",
            "yellow",
            "green", 
            "black",
            "white",
        ];
        
        protected $mapTemplates = [
            "earth",
            "middle_earth",
            "after_apocalypse"
        ];


        public function __construct() {

        }


        public function checkUserMayCreateMatch($user) {

                if($user->joinedMatch !== null){
                    throw new GameException("ALREADY.JOINED");
                }

        }


        public function checkUserCanDeleteMatch($user, $match) {

                if($user->id !== $match->createdBy->id){
                    throw new GameException("CANNOT.DELETE.MATCH");
                }

        }


        public function checkUserCanAdministrateMatch($user, $match) {

                if($user->id !== $match->createdBy->id){
                    throw new GameException("CANNOT.DELETE.MATCH");
                }

        }

        
        public function checkUserMayJoinMatch($user, $match) {

                if($user->joinedMatch !== null){
                    throw new GameException("ALREADY.JOINED");
                }
                if($match->state !== self::MATCHSTATE_WAITING_FOR_PLAYERJOINS){
                    throw new GameException("MATCH.CLOSED");
                }
                
                if(!$match->public){
                    $isParticipant = false;
                    foreach($match->thread->participants as $participant){
                        if($user->id == $participant->user->id){
                            $isParticipant = true;
                        }
                    }
                    if(!$isParticipant){
                        throw new GameException("ONLY.INVITED.USERS");
                    }
                }

        }


        public function getAllMatches(){

            return Match::all();

        }


        public function getAllPublicMatches(){

            return Match::where("public", true)->get();

        }


        public function getAllPublicWaitingMatches(){

            return Match
                ::where("public", true)
                ->where("state", self::MATCHSTATE_WAITING_FOR_PLAYERJOINS)
                ->get();

        }
        
        
        public function getMapNames() {

            return $this->mapTemplates;

        }


        public function getMatchForId($id){

            $match = Match::find($id);
            if(!$match) {
                throw new GameException("MATCH.NOT.FOUND");
            }
            return $match;

        }


        public function getMatchForJoinId($joinId){

            $match = Match::where("joinid", $joinId)->first();
            
            if(!$match) {
                throw new GameException("MATCH.NOT.FOUND");
            }
            return $match;


        }


        public function getMatchForUser($user){

            $match = $user->joinedMatch;
            if(!$match) {
                throw new GameException("MATCH.NOT.FOUND");
            }
            return $match;


        }
        
        
        public function getUntakenColorSchemesForMatch($match) {
            
            $takenColorSchemes = array();
            
            $users = $match->joinedUsers;
            foreach($users as $user){
                array_push($takenColorSchemes, $user->matchcolor);
            }
            
            $allowedColorSchemes = array_diff(self::COLORSCHEMES, $takenColorSchemes);
            
            return $allowedColorSchemes;
            
        }


        public function createMatch($name, $mapName, $maxUsers, $creatorUser, $thread, $isPublic) {

                $match = new Match();
                $match->name = $name;
                $match->createdBy()->associate($creatorUser);
                $match->maxusers = $maxUsers;
                $match->public = $isPublic;
                $token = IdTokenService::createToken();
                while(Match::where("joinid", $token)->first()){
                    $token = IdTokenService::createToken();
                }
                $match->mapname = $mapName;
                $match->joinid = $token;
                $match->state = self::MATCHSTATE_WAITING_FOR_PLAYERJOINS;
                $match->thread()->associate($thread);
                
                $match->save();

                return $match;

        }

        
        public function joinUserToMatch($match, $user, $colorScheme) {

                if($match->state !== self::MATCHSTATE_WAITING_FOR_PLAYERJOINS){
                    throw new GameException("MATCH.CLOSED");
                }
                $user->joinedMatch()->associate($match);
                $user->save();
                $match->load("joinedUsers");
                
                $user->matchcolor = $colorScheme;
                $user->save();
                
                if(count($match->joinedUsers) >= $match->maxusers){
                    $this->startMatch($match);
                }


        }

        
        public function cancelMatch($match) {

                foreach ($match->joinedUsers as $joinedUser) {
                    $result = $joinedUser->joinedMatch()->dissociate();
                    $joinedUser->matchorder = 0;
                    $joinedUser->save();
                }

                $match->delete();

        }

        
        public function startMatch($match){
            
                $i = 1;
                $joinedUsers = $match->joinedUsers;
                $shuffledJoinedUsers = $joinedUsers->shuffle();
                foreach($shuffledJoinedUsers as $randomJoinedUser){
                    $randomJoinedUser->matchorder = $i;
                    $randomJoinedUser->save();
                    $i++;
                }
                
                $match->state = self::MATCHSTATE_START;
                $match->save();
                

        }


        public function changeMatchData($match, $maxUsers, $isPublic) {
            
            $match->maxusers = (int) $maxUsers;
            $match->public = (boolean) $isPublic;
            $match->save();
            
            if(count($match->joinedUsers) >= $match->maxusers){
                $this->startMatch($match);
            }

        }
    
}
