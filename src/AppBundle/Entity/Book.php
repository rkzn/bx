<?php

namespace AppBundle\Entity;

/**
 * Book
 */
class Book
{
    /**
     * @var string
     */
    private $ISBN = '';

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $author;

    /**
     * @var integer
     */
    private $year;

    /**
     * @var string
     */
    private $publisher;

    /**
     * @var string
     */
    private $imageS;

    /**
     * @var string
     */
    private $imageM;

    /**
     * @var string
     */
    private $imageL;


    /**
     * Set iSBN
     *
     * @param string $iSBN
     *
     * @return Book
     */
    public function setISBN($iSBN)
    {
        $this->ISBN = $iSBN;

        return $this;
    }

    /**
     * Get iSBN
     *
     * @return string
     */
    public function getISBN()
    {
        return $this->ISBN;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Book
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Book
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set year
     *
     * @param integer $year
     *
     * @return Book
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set publisher
     *
     * @param string $publisher
     *
     * @return Book
     */
    public function setPublisher($publisher)
    {
        $this->publisher = $publisher;

        return $this;
    }

    /**
     * Get publisher
     *
     * @return string
     */
    public function getPublisher()
    {
        return $this->publisher;
    }

    /**
     * Set imageS
     *
     * @param string $imageS
     *
     * @return Book
     */
    public function setImageS($imageS)
    {
        $this->imageS = $imageS;

        return $this;
    }

    /**
     * Get imageS
     *
     * @return string
     */
    public function getImageS()
    {
        return $this->imageS;
    }

    /**
     * Set imageM
     *
     * @param string $imageM
     *
     * @return Book
     */
    public function setImageM($imageM)
    {
        $this->imageM = $imageM;

        return $this;
    }

    /**
     * Get imageM
     *
     * @return string
     */
    public function getImageM()
    {
        return $this->imageM;
    }

    /**
     * Set imageL
     *
     * @param string $imageL
     *
     * @return Book
     */
    public function setImageL($imageL)
    {
        $this->imageL = $imageL;

        return $this;
    }

    /**
     * Get imageL
     *
     * @return string
     */
    public function getImageL()
    {
        return $this->imageL;
    }
    /**
     * @var string
     */
    private $ImageUrlS;

    /**
     * @var string
     */
    private $ImageUrlM;

    /**
     * @var string
     */
    private $ImageUrlL;


    /**
     * Set imageUrlS
     *
     * @param string $imageUrlS
     *
     * @return Book
     */
    public function setImageUrlS($imageUrlS)
    {
        $this->ImageUrlS = $imageUrlS;

        return $this;
    }

    /**
     * Get imageUrlS
     *
     * @return string
     */
    public function getImageUrlS()
    {
        return $this->ImageUrlS;
    }

    /**
     * Set imageUrlM
     *
     * @param string $imageUrlM
     *
     * @return Book
     */
    public function setImageUrlM($imageUrlM)
    {
        $this->ImageUrlM = $imageUrlM;

        return $this;
    }

    /**
     * Get imageUrlM
     *
     * @return string
     */
    public function getImageUrlM()
    {
        return $this->ImageUrlM;
    }

    /**
     * Set imageUrlL
     *
     * @param string $imageUrlL
     *
     * @return Book
     */
    public function setImageUrlL($imageUrlL)
    {
        $this->ImageUrlL = $imageUrlL;

        return $this;
    }

    /**
     * Get imageUrlL
     *
     * @return string
     */
    public function getImageUrlL()
    {
        return $this->ImageUrlL;
    }
}
