import React from 'react';
import { Facebook, Twitter, Instagram, Github as GitHub, MessageCircle } from 'lucide-react';

const Footer: React.FC = () => {
  return (
    <footer className="bg-purple-950 text-purple-200 py-8">
      <div className="container mx-auto px-4">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
          <div className="space-y-4">
            <h3 className="text-white text-lg font-bold">GameVault</h3>
            <p className="text-sm text-purple-300">
              Your ultimate destination for game discovery, community, and immersive experiences.
            </p>
            <div className="flex space-x-4">
              <a href="#" className="text-purple-400 hover:text-white transition-colors">
                <Facebook size={20} />
              </a>
              <a href="#" className="text-purple-400 hover:text-white transition-colors">
                <Twitter size={20} />
              </a>
              <a href="#" className="text-purple-400 hover:text-white transition-colors">
                <Instagram size={20} />
              </a>
              <a href="#" className="text-purple-400 hover:text-white transition-colors">
                <GitHub size={20} />
              </a>
            </div>
          </div>
          
          <div>
            <h4 className="text-white font-medium mb-4">Quick Links</h4>
            <ul className="space-y-2 text-sm">
              <li><a href="#" className="hover:text-white transition-colors">Home</a></li>
              <li><a href="#" className="hover:text-white transition-colors">Browse Games</a></li>
              <li><a href="#" className="hover:text-white transition-colors">New Releases</a></li>
              <li><a href="#" className="hover:text-white transition-colors">Top Rated</a></li>
              <li><a href="#" className="hover:text-white transition-colors">Upcoming</a></li>
            </ul>
          </div>
          
          <div>
            <h4 className="text-white font-medium mb-4">Support</h4>
            <ul className="space-y-2 text-sm">
              <li><a href="#" className="hover:text-white transition-colors">Help Center</a></li>
              <li><a href="#" className="hover:text-white transition-colors">Community</a></li>
              <li><a href="#" className="hover:text-white transition-colors">Game Support</a></li>
              <li><a href="#" className="hover:text-white transition-colors">Terms of Service</a></li>
              <li><a href="#" className="hover:text-white transition-colors">Privacy Policy</a></li>
            </ul>
          </div>
          
          <div>
            <h4 className="text-white font-medium mb-4">Contact Us</h4>
            <div className="flex items-center space-x-2 mb-2 text-sm">
              <MessageCircle size={16} />
              <span>support@gamevault.com</span>
            </div>
            <p className="text-sm mb-4">
              Subscribe to our newsletter for updates on new games and features.
            </p>
            <div className="flex">
              <input 
                type="email" 
                placeholder="Your email" 
                className="px-3 py-2 rounded-l-lg bg-purple-900 border border-purple-700 text-white text-sm focus:outline-none focus:ring-1 focus:ring-purple-500 w-full"
              />
              <button className="bg-purple-600 hover:bg-purple-500 px-3 py-2 rounded-r-lg text-white transition-colors text-sm">
                Subscribe
              </button>
            </div>
          </div>
        </div>
        
        <div className="border-t border-purple-800 mt-8 pt-4 text-sm text-center">
          <p>© 2025 GameVault. All rights reserved.</p>
        </div>
      </div>
    </footer>
  );
};

export default Footer;