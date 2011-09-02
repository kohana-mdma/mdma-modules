<?php defined('SYSPATH') or die('No direct script access.');

class MDMA_Controller_Media extends Controller {
	
	public function action_move()
	{
		MDMA_Media::factory()->move();
	}
	
	public function action_get()
	{
		// Get the file path from the request
		$file = $this->request->param('file');
        $dir = $this->request->param('dir');

		// Find the file extension
		$ext = pathinfo($file, PATHINFO_EXTENSION);

		// Remove the extension from the filename
		$file = substr($file, 0, -(strlen($ext) + 1));
		$file = Kohana::find_file('media', $dir.'/'.$file, $ext);
		if ($file)
		{
			// Check if the browser sent an "if-none-match: <etag>" header, and tell if the file hasn't changed
			$this->response->check_cache(sha1($this->request->uri()).filemtime($file), $this->request);

			// Send the file content as the response
			$this->response->body(file_get_contents($file));

			// Set the proper headers to allow caching
			$this->response->headers('content-type',  File::mime_by_ext($ext));
			$this->response->headers('last-modified', date('r', filemtime($file)));
		}
		else
		{
			// Return a 404 status
			$this->response->status(404);
		}
	}
			
}
// End MDMA Media