<?php

namespace App\Policies;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policies\Basic;
use Spatie\Csp\Policies\Policy;
use Spatie\Csp\Value;

class CspPolicy extends Basic
{
    public function configure()
    {
//        parent::configure();
//        $this
//            ->addDirective(Directive::BASE, Keyword::SELF)
//            ->addDirective(Directive::CONNECT, Keyword::SELF)
//            ->addDirective(Directive::DEFAULT, Keyword::SELF)
//            ->addDirective(Directive::IMG, [Keyword::SELF, 'data:'])
//            ->addDirective(Directive::IMG, 's3.amazonaws.com')
//            ->addDirective(Directive::MEDIA, Keyword::SELF)
//            ->addDirective(Directive::OBJECT, Keyword::NONE)
//            ->addDirective(Directive::SCRIPT, Keyword::SELF)
//            ->addDirective(Directive::STYLE, Keyword::SELF);
//        $this->addDirective(Directive::STYLE, 'fonts.googleapis.com');
//        $this->addDirective(Directive::STYLE, 'cdnjs.cloudflare.com');
//        $this->addDirective(Directive::SCRIPT, 'cdnjs.cloudflare.com');
//        $this->addDirective(Directive::DEFAULT, 'fonts.gstatic.com');
//        $this->addDirective(Directive::FONT, 'cdnjs.cloudflare.com');
//        $this->addDirective(Directive::FONT, [Keyword::SELF, 'data:', 'fonts.gstatic.com']);
//        $this->addDirective(Directive::FORM_ACTION, [Keyword::SELF, 'antonioante.shop', 'dev-backoffice.antonioante.shop']);
//        $this->addDirective(Directive::FONT, Keyword::SELF);
//        $this->addDirective(Directive::FRAME, 'www.strava.com');
//        $this->addDirective(Directive::UPGRADE_INSECURE_REQUESTS, Value::NO_VALUE);
//        $this->addDirective(Directive::BLOCK_ALL_MIXED_CONTENT, Value::NO_VALUE);
//        $this->addDirective(Directive::STYLE, Keyword::UNSAFE_INLINE);
//        $this->addDirective(Directive::SCRIPT, Keyword::UNSAFE_INLINE);
    }
}
