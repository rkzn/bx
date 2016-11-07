<?php

namespace AppBundle\Entity;

/**
 * Rating
 */
class Rating
{
    /**
     * @var integer
     */
    private $UserId = '0';

    /**
     * @var string
     */
    private $ISBN = '';

    /**
     * @var integer
     */
    private $rating = '0';


    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Rating
     */
    public function setUserId($userId)
    {
        $this->UserId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->UserId;
    }

    /**
     * Set iSBN
     *
     * @param string $iSBN
     *
     * @return Rating
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
     * Set rating
     *
     * @param integer $rating
     *
     * @return Rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return integer
     */
    public function getRating()
    {
        return $this->rating;
    }
}

