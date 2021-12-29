<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Article\Apoplication;

use DateTime;
use NachoBrito\TTBot\Article\Application\HandleTwitterSummarizeRequestsCommand;
use NachoBrito\TTBot\Article\Application\HandleTwitterSummarizeRequestsHandler;
use NachoBrito\TTBot\Article\Domain\ArticleLoader;
use NachoBrito\TTBot\Article\Domain\ArticleSummarizer;
use NachoBrito\TTBot\Article\Domain\Model\Article;
use NachoBrito\TTBot\Article\Domain\Model\ArticleSummary;
use NachoBrito\TTBot\Article\Domain\URLExtractor;
use NachoBrito\TTBot\Common\Domain\RateLimiter;
use NachoBrito\TTBot\Common\Infraestructure\BufferedLogger;
use NachoBrito\TTBot\Twitter\Application\TwitterService;
use NachoBrito\TTBot\Twitter\Domain\Model\Tweet;
use PHPUnit\Framework\TestCase;

/**
 * 
 *
 * @author nacho
 */
class HandleTwitterSummarizeRequestsHandlerTest extends TestCase {

    /**
     * 
     * @return void
     */
    public function testDoNothingIfNoMentions(): void {

        $command = new HandleTwitterSummarizeRequestsCommand();

        $summarizer = $this
                ->getMockBuilder(ArticleSummarizer::class)
                ->getMock();
        $summarizer
                ->expects($this->never())
                ->method('summarize')
        ;

        $loader = $this
                ->getMockBuilder(ArticleLoader::class)
                ->getMock();
        $loader
                ->expects($this->never())
                ->method('loadArticle')
        ;

        $twitter = $this
                ->getMockBuilder(TwitterService::class)
                ->disableOriginalConstructor()
                ->getMock();

        $twitter
                ->expects($this->once())
                ->method('getNewMentions')
                ->willReturn([]);

        $twitter
                ->expects($this->never())
                ->method('postReplyThread');

        $urlExtractor = $this
                ->getMockBuilder(URLExtractor::class)
                ->getMock();

        $urlExtractor
                ->expects($this->never())
                ->method('getUrls');

        $rateLimiter = $this
                ->getMockBuilder(RateLimiter::class)
                ->getMock()
                ;
                
        $rateLimiter
                ->expects($this->any())
                ->method('actionAllowed')
                ->willReturn(TRUE);
        
        $logger = new BufferedLogger();

        $handler = new HandleTwitterSummarizeRequestsHandler($twitter, $logger, $summarizer, $loader, $urlExtractor, $rateLimiter);

        $handler($command);
    }

    /**
     * 
     * @return void
     */
    public function testReplyThread(): void {
        $max_tweets = 3;
        putenv('THREADS_MAX_TWEETS=3');
        $url = 'TheURL';
        $sentences = [
            'sentence 1',
            'sentence 2'
        ];
        
        $tweets = [
            '"sentence 1" TheURL',
            '"sentence 2" TheURL',
        ];
        $command = new HandleTwitterSummarizeRequestsCommand();
        $article = (new Article())
                ->setMetadata(["meta" => "data"])
                ->setText("Text")
                ->setTitle("Title")
                ->setUrl($url);
        
        $summarizer = $this
                ->getMockBuilder(ArticleSummarizer::class)
                ->getMock();
        
        $summarizer
                ->expects($this->once())
                ->method('summarize')
                ->with($article)
                ->willReturn(new ArticleSummary($article, $sentences));
        ;



        $loader = $this
                ->getMockBuilder(ArticleLoader::class)
                ->getMock();
        $loader
                ->expects($this->once())
                ->method('loadArticle')
                ->with($url)
                ->willReturn($article)
        ;
        $twitter = $this
                ->getMockBuilder(TwitterService::class)
                ->disableOriginalConstructor()
                ->getMock();

        $mention = (new Tweet())
                ->setAuthorId('authorId')
                ->setAuthorName('authorName')
                ->setCreatedAt(new DateTime())
                ->setId('mentionId')
                ->setLang('lang')
                ->setText('mention Text')
        ;
        $twitter
                ->expects($this->once())
                ->method('getNewMentions')
                ->willReturn([$mention]);
        
        $twitter
                ->expects($this->once())
                ->method('postReplyThread')
                ->with($mention,$tweets,$max_tweets);
        

        $urlExtractor = $this
                ->getMockBuilder(URLExtractor::class)
                ->getMock();

        $urlExtractor
                ->expects($this->once())
                ->method('getUrls')
                ->with($mention->getText())
                ->willReturn([$url]);

        $logger = new BufferedLogger();


        $rateLimiter = $this
                ->getMockBuilder(RateLimiter::class)
                ->getMock()
                ;
                
        $rateLimiter
                ->expects($this->any())
                ->method('actionAllowed')
                ->willReturn(TRUE);
        
        $handler = new HandleTwitterSummarizeRequestsHandler($twitter, $logger, $summarizer, $loader, $urlExtractor, $rateLimiter);

        $handler($command);       
        
        putenv('THREADS_MAX_TWEETS');
    }

    public function testNothingDoneIfNoURL(): void {

        $command = new HandleTwitterSummarizeRequestsCommand();

        $summarizer = $this
                ->getMockBuilder(ArticleSummarizer::class)
                ->getMock();
        $summarizer
                ->expects($this->never())
                ->method('summarize')
        ;

        $loader = $this
                ->getMockBuilder(ArticleLoader::class)
                ->getMock();
        $loader
                ->expects($this->never())
                ->method('loadArticle')
        ;

        $twitter = $this
                ->getMockBuilder(TwitterService::class)
                ->disableOriginalConstructor()
                ->getMock();

        $mention = (new Tweet())
                ->setAuthorId('authorId')
                ->setAuthorName('authorName')
                ->setCreatedAt(new DateTime())
                ->setId('mentionId')
                ->setLang('lang')
                ->setText('mention Text')
        ;
        $twitter
                ->expects($this->once())
                ->method('getNewMentions')
                ->willReturn([$mention]);
        $twitter
                ->expects($this->never())
                ->method('postReplyThread');

        $urlExtractor = $this
                ->getMockBuilder(URLExtractor::class)
                ->getMock();

        $urlExtractor
                ->expects($this->once())
                ->method('getUrls')
                ->with($mention->getText())
                ->willReturn([]);

        $logger = new BufferedLogger();

        $rateLimiter = $this
                ->getMockBuilder(RateLimiter::class)
                ->getMock()
                ;
                
        $rateLimiter
                ->expects($this->any())
                ->method('actionAllowed')
                ->willReturn(TRUE);
        $handler = new HandleTwitterSummarizeRequestsHandler($twitter, $logger, $summarizer, $loader, $urlExtractor, $rateLimiter);

        $handler($command);
    }

}
