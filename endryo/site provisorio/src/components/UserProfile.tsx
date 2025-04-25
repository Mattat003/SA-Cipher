import React from 'react';
import { LogOut, Settings, User, Heart, Star, Shield } from 'lucide-react';
import { currentUser } from '../data/user';

interface UserProfileProps {
  onClose: () => void;
}

const UserProfile: React.FC<UserProfileProps> = ({ onClose }) => {
  return (
    <div className="bg-purple-800 text-white p-4">
      <div className="flex items-start mb-4">
        <img 
          src={currentUser.avatar} 
          alt={currentUser.username} 
          className="w-16 h-16 rounded-full object-cover border-2 border-purple-400" 
        />
        <div className="ml-3">
          <h3 className="font-bold text-lg">{currentUser.username}</h3>
          <div className="flex items-center">
            <div className="bg-green-500 h-2 w-2 rounded-full mr-2"></div>
            <span className="text-sm">Online</span>
          </div>
          <div className="mt-1 flex items-center">
            <div className="bg-purple-500 text-xs rounded-full px-2 py-0.5 flex items-center">
              <Star size={12} className="mr-1" />
              <span>Level {currentUser.level}</span>
            </div>
          </div>
        </div>
      </div>

      <div className="grid grid-cols-2 gap-2 mb-4">
        <div className="bg-purple-700 rounded-lg p-3 flex flex-col items-center justify-center">
          <span className="text-xl font-bold">{currentUser.gamesOwned}</span>
          <span className="text-xs text-purple-300">Games</span>
        </div>
        <div className="bg-purple-700 rounded-lg p-3 flex flex-col items-center justify-center">
          <span className="text-xl font-bold">{currentUser.friends}</span>
          <span className="text-xs text-purple-300">Friends</span>
        </div>
      </div>

      <div className="space-y-2">
        <button className="w-full py-2 px-3 flex items-center rounded-lg hover:bg-purple-700 transition">
          <User size={16} className="mr-2" />
          <span>View Profile</span>
        </button>
        <button className="w-full py-2 px-3 flex items-center rounded-lg hover:bg-purple-700 transition">
          <Heart size={16} className="mr-2" />
          <span>Wishlist</span>
        </button>
        <button className="w-full py-2 px-3 flex items-center rounded-lg hover:bg-purple-700 transition">
          <Shield size={16} className="mr-2" />
          <span>Account Settings</span>
        </button>
        <hr className="border-purple-600 my-2" />
        <button className="w-full py-2 px-3 flex items-center rounded-lg hover:bg-purple-700 transition">
          <Settings size={16} className="mr-2" />
          <span>Settings</span>
        </button>
        <button className="w-full py-2 px-3 flex items-center rounded-lg hover:bg-purple-700 transition text-red-400">
          <LogOut size={16} className="mr-2" />
          <span>Sign Out</span>
        </button>
      </div>
    </div>
  );
};

export default UserProfile;