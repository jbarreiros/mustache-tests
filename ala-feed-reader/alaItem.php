<?php
/*
 * Yes, this is a weird beast. A SimpleXMLElement subclass with some
 * DOMDocument code buried in it.
 */

class alaItem extends SimpleXMLElement
{
    /**
     * contents of SimpleXMLElement for ala feed:
     * title, link, guid, description
     */

    const TYPE_ISSUE = 1;
    const TYPE_COLUMN = 2;
    const TYPE_BLOG = 3;

    /**
     * The article type.
     *
     * Note:
     * http://php.net/manual/en/class.simplexmlelement.php#100811
     *
     * @var resource
     */
    protected $type;

    /**
     * Gets the articles type (issue, column, blog).
     *
     * @return int
     */
    public function getArticleType() {
        $type = self::TYPE_BLOG;
        if( strpos( $this->guid, 'alistapart.com/article/' ) !== false ) {
            $type = self::TYPE_ISSUE;
        }
        elseif( strpos( $this->guid, 'alistapart.com/column/' ) !== false ) {
            $type = self::TYPE_COLUMN;
        }

        $this->type = $type;
        return $type;
    }

    /**
     * Gets the issue number associated with the article (if its type is
     * "issue").
     *
     * @return int
     */
    public function getIssueNum() {
        /*
        Clearly, the following will slow the script immensely. Wanted to try
        out the DOM extension.
         */

        $num = 0;
        if( (int) $this->type === self::TYPE_ISSUE ) {
            $doc = new DOMDocument();
            @$doc->loadHTMLFile($this->guid);

            $find = new DOMXPath($doc);
            $nodes = $find->query("//a[contains(@class, 'issue-number')]");
            foreach( $nodes as $node ) {
               $num = substr($node->textContent,-3);
               break;
            }

            /*$figures = $doc->getElementsByTagName('figure');
            foreach($figures as $figure ) {
                if( in_array( $figure->getAttribute('class'), array( 'wide-hero', 'tall-hero' ) ) ) {
                    $links = $figure->getElementsByTagName('a');
                    foreach( $links as $link ) {
                        if( $link->getAttribute('class') == 'issue-number' ) {
                            echo $link->textContent;
                            $num = substr($link->textContent,-3);
                            break 2;
                        }
                    }
                }
            }*/
        }
        return $num;
    }

    /**
     * Utility method to return the element as an associative array for use
     * within a mustache template.
     *
     * @return array
     */
    public function toTemplateArray() {
        return array(
            'url'   => (string) $this->guid,
            'title' => (string) $this->title
        );
    }

}
?>