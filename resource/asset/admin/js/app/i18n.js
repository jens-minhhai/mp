// https://phraseapp.com/blog/posts/jquery-i18n-the-advanced-guide/

import '../component/i18n/CLDRPluralRuleParser/CLDRPluralRuleParser';
import '../component/i18n/jquery.i18n';
import '../component/i18n/jquery.i18n.messagestore';
import '../component/i18n/jquery.i18n.fallbacks';
import '../component/i18n/jquery.i18n.language';
import '../component/i18n/jquery.i18n.parser';
import '../component/i18n/jquery.i18n.emitter';
import '../component/i18n/jquery.i18n.emitter.bidi';

import en from './i18n/en.json';
import vi from './i18n/vi.json';

$.i18n()
    .load({
        en,
        vi
    });
