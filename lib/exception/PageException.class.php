<?php
class PageException extends Exception
{
    const PAGE_NO_ID = 1;
    const PAGE_NO_HANDLER = 2;
    const PAGE_INVALID_HANDLER = 3;
    const PAGE_NOT_AVAILABLE = 4;
    const BLOCK_INVALID_HANDLER = 5;
    const CONTENT_INVALID_HANDLER = 6;
    const PAGE_IS_NOT_INDEX = 7;
}