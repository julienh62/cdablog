<?php


namespace App\Voters;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class PostVoter extends Voter
{
    // these strings are just invented: you can use anything
    
    const EDIT = 'post-edit';
    const REMOVE = 'post-remove';

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::REMOVE, self::EDIT])) {
            return false;
        }

        // only vote on `Post` objects
        if (!$subject instanceof Post) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        //dd("ok");
        $user = $token->getUser();
        /**@var Post $post */
        $post = $subject;

       if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }  

        // you know $subject is a Post object, thanks to `supports()`
        /** @var Post $post */
        $post = $subject;
       // if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
        //$roleUser = 'ROLE_ADMIN';
       // }
        //dd($roleUser);

        if ($attribute == self::EDIT){
            if($post->getUser() == $user || in_array('ROLE_ADMIN', $user->getRoles())  ){
                return true;
            }
        }

        if ($attribute == self::REMOVE){
            if($post->getUser() == $user ||   in_array('ROLE_ADMIN', $user->getRoles()) ){
                return true;
            }
        }
           return false;
           } 
        }
  